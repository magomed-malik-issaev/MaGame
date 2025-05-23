<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RawgApiService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.rawg.io/api';

    // Limite quotidienne de requêtes (à ajuster selon votre plan RAWG)
    protected $dailyLimit = 1000;

    // Clé de cache pour le compteur de requêtes
    protected $requestCountKey = 'rawg_api_request_count';

    // Durée de vie du cache pour les résultats de l'API (en minutes)
    protected $cacheDuration = 60 * 24; // 24 heures par défaut

    public function __construct()
    {
        $this->apiKey = config('services.rawg.key');

        // Log pour le débogage
        Log::info('RawgApiService initialisé avec la clé: ' . (!empty($this->apiKey) ? 'Clé présente' : 'Clé absente'));
    }

    /**
     * Vérifie si la limite quotidienne de requêtes a été atteinte
     * 
     * @return bool
     */
    protected function hasReachedDailyLimit()
    {
        $today = Carbon::now()->format('Y-m-d');
        $countData = Cache::get($this->requestCountKey, []);

        // Si la date du compteur n'est pas aujourd'hui, on réinitialise
        if (!isset($countData['date']) || $countData['date'] !== $today) {
            $countData = [
                'date' => $today,
                'count' => 0
            ];
            Cache::put($this->requestCountKey, $countData, Carbon::now()->endOfDay());
        }

        return $countData['count'] >= $this->dailyLimit;
    }

    /**
     * Incrémente le compteur de requêtes
     */
    protected function incrementRequestCount()
    {
        $today = Carbon::now()->format('Y-m-d');
        $countData = Cache::get($this->requestCountKey, [
            'date' => $today,
            'count' => 0
        ]);

        // Si la date du compteur n'est pas aujourd'hui, on réinitialise
        if ($countData['date'] !== $today) {
            $countData = [
                'date' => $today,
                'count' => 1
            ];
        } else {
            $countData['count']++;
        }

        // Stocke le compteur jusqu'à la fin de la journée
        Cache::put($this->requestCountKey, $countData, Carbon::now()->endOfDay());

        // Log si on approche de la limite
        if ($countData['count'] > $this->dailyLimit * 0.8) {
            Log::warning("RAWG API: {$countData['count']}/{$this->dailyLimit} requêtes utilisées aujourd'hui. Attention à la limite!");
        }
    }

    /**
     * Retourne le nombre de requêtes effectuées aujourd'hui et la limite
     * 
     * @return array
     */
    public function getRequestStats()
    {
        $today = Carbon::now()->format('Y-m-d');
        $countData = Cache::get($this->requestCountKey, [
            'date' => $today,
            'count' => 0
        ]);

        return [
            'date' => $countData['date'],
            'count' => $countData['count'],
            'limit' => $this->dailyLimit,
            'remaining' => $this->dailyLimit - $countData['count'],
            'percentage' => round(($countData['count'] / $this->dailyLimit) * 100, 2)
        ];
    }

    public function getPopularGames($page = 1, $pageSize = 12)
    {
        $cacheKey = "games_popular_page_{$page}_size_{$pageSize}";
        $cacheDuration = $this->cacheDuration;

        // Utiliser le cache peu importe l'environnement pour économiser les requêtes
        return Cache::remember($cacheKey, $cacheDuration, function () use ($page, $pageSize) {
            return $this->makeRequest('/games', [
                'ordering' => '-rating',
                'page' => $page,
                'page_size' => $pageSize
            ]);
        });
    }

    public function getNewGames($page = 1, $pageSize = 12)
    {
        $cacheKey = "games_new_page_{$page}_size_{$pageSize}";
        $cacheDuration = $this->cacheDuration;

        // Utiliser le cache peu importe l'environnement pour économiser les requêtes
        return Cache::remember($cacheKey, $cacheDuration, function () use ($page, $pageSize) {
            return $this->makeRequest('/games', [
                'ordering' => '-released',
                'page' => $page,
                'page_size' => $pageSize
            ]);
        });
    }

    /**
     * Récupère tous les jeux sans filtre particulier
     *
     * @param int $page Numéro de page
     * @param int $pageSize Nombre de jeux par page
     * @param array $filters Filtres supplémentaires (genres, platforms, etc.)
     * @return array
     */
    public function getAllGames($page = 1, $pageSize = 20, $filters = [])
    {
        // Construction de la clé de cache incluant les filtres
        $filterKey = !empty($filters) ? md5(json_encode($filters)) : 'no_filters';
        $cacheKey = "games_all_page_{$page}_size_{$pageSize}_filters_{$filterKey}";
        $cacheDuration = $this->cacheDuration;

        // Paramètres de base
        $params = [
            'page' => $page,
            'page_size' => $pageSize
        ];

        // Fusion des filtres supplémentaires
        if (!empty($filters)) {
            $params = array_merge($params, $filters);
        }

        // Utiliser le cache peu importe l'environnement pour économiser les requêtes
        return Cache::remember($cacheKey, $cacheDuration, function () use ($params) {
            return $this->makeRequest('/games', $params);
        });
    }

    public function getGameDetails($id)
    {
        $cacheKey = "game_details_{$id}";
        $cacheDuration = $this->cacheDuration;

        // Utiliser le cache peu importe l'environnement pour économiser les requêtes
        return Cache::remember($cacheKey, $cacheDuration, function () use ($id) {
            return $this->makeRequest("/games/{$id}");
        });
    }

    public function searchGames($query, $page = 1, $pageSize = 12)
    {
        // Utiliser une clé de cache unique pour les recherches
        $cacheKey = "search_" . md5($query) . "_page_{$page}_size_{$pageSize}";

        // Cache de courte durée pour les recherches (1 heure)
        return Cache::remember($cacheKey, 60, function () use ($query, $page, $pageSize) {
            return $this->makeRequest('/games', [
                'search' => $query,
                'page' => $page,
                'page_size' => $pageSize
            ]);
        });
    }

    /**
     * Récupère la liste des genres disponibles
     * 
     * @return array
     */
    public function getGenres()
    {
        $cacheKey = "game_genres";

        // Cache de longue durée pour les genres (1 semaine)
        return Cache::remember($cacheKey, 60 * 24 * 7, function () {
            return $this->makeRequest("/genres");
        });
    }

    protected function makeRequest($endpoint, $params = [])
    {
        // Vérifier si la clé API est définie
        if (empty($this->apiKey)) {
            Log::error('Clé API RAWG manquante');
            return [
                'results' => [],
                'error' => 'Clé API non configurée'
            ];
        }

        // Vérifier si on a atteint la limite quotidienne
        if ($this->hasReachedDailyLimit()) {
            Log::error("RAWG API: Limite quotidienne atteinte ({$this->dailyLimit} requêtes)");
            return [
                'results' => [],
                'error' => "Limite quotidienne d'API atteinte. Veuillez réessayer demain."
            ];
        }

        $params['key'] = $this->apiKey;

        try {
            Log::info('Requête API RAWG', [
                'endpoint' => $endpoint,
                'params' => array_merge($params, ['key' => 'XXXX']), // Masquer la clé API dans les logs
            ]);

            // Définir un timeout pour éviter que l'application ne reste bloquée
            $response = Http::timeout(5)->get($this->baseUrl . $endpoint, $params);

            // Incrémenter le compteur de requêtes uniquement si la requête est effectuée (pas depuis le cache)
            $this->incrementRequestCount();

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Réponse API réussie', ['status' => $response->status()]);

                // Vérifier si nous avons des résultats valides
                if (isset($data['results']) && is_array($data['results'])) {
                    // Filtrer les jeux sans images si nécessaire
                    // $data['results'] = array_filter($data['results'], function($game) {
                    //     return isset($game['background_image']) && !empty($game['background_image']);
                    // });
                }

                return $data;
            } else {
                Log::error('Erreur API RAWG', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'results' => [],
                    'error' => 'Erreur API: ' . $response->status() . ' - ' . $response->body()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception lors de la requête API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'results' => [],
                'error' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
}
