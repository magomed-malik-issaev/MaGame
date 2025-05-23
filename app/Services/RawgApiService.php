<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RawgApiService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.rawg.io/api';

    public function __construct()
    {
        $this->apiKey = config('services.rawg.key');

        // Log pour le débogage
        Log::info('RawgApiService initialisé avec la clé: ' . (!empty($this->apiKey) ? 'Clé présente' : 'Clé absente'));
    }

    public function getPopularGames($page = 1, $pageSize = 12)
    {
        $cacheKey = "games_popular_page_{$page}_size_{$pageSize}";

        // Utiliser le cache en production
        if (app()->environment('production')) {
            return Cache::remember($cacheKey, 60 * 24, function () use ($page, $pageSize) {
                return $this->makeRequest('/games', [
                    'ordering' => '-rating',
                    'page' => $page,
                    'page_size' => $pageSize
                ]);
            });
        }

        // En développement, on désactive le cache pour faciliter le débogage
        return $this->makeRequest('/games', [
            'ordering' => '-rating',
            'page' => $page,
            'page_size' => $pageSize
        ]);
    }

    public function getNewGames($page = 1, $pageSize = 12)
    {
        $cacheKey = "games_new_page_{$page}_size_{$pageSize}";

        // Utiliser le cache en production
        if (app()->environment('production')) {
            return Cache::remember($cacheKey, 60 * 24, function () use ($page, $pageSize) {
                return $this->makeRequest('/games', [
                    'ordering' => '-released',
                    'page' => $page,
                    'page_size' => $pageSize
                ]);
            });
        }

        // En développement, on désactive le cache pour faciliter le débogage
        return $this->makeRequest('/games', [
            'ordering' => '-released',
            'page' => $page,
            'page_size' => $pageSize
        ]);
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

        // Paramètres de base
        $params = [
            'page' => $page,
            'page_size' => $pageSize
        ];

        // Fusion des filtres supplémentaires
        if (!empty($filters)) {
            $params = array_merge($params, $filters);
        }

        // Utiliser le cache en production
        if (app()->environment('production')) {
            return Cache::remember($cacheKey, 60 * 24, function () use ($params) {
                return $this->makeRequest('/games', $params);
            });
        }

        // En développement, on désactive le cache pour faciliter le débogage
        return $this->makeRequest('/games', $params);
    }

    public function getGameDetails($id)
    {
        $cacheKey = "game_details_{$id}";

        // Utiliser le cache en production
        if (app()->environment('production')) {
            return Cache::remember($cacheKey, 60 * 24, function () use ($id) {
                return $this->makeRequest("/games/{$id}");
            });
        }

        // En développement, on désactive le cache pour faciliter le débogage
        return $this->makeRequest("/games/{$id}");
    }

    public function searchGames($query, $page = 1, $pageSize = 12)
    {
        return $this->makeRequest('/games', [
            'search' => $query,
            'page' => $page,
            'page_size' => $pageSize
        ]);
    }

    /**
     * Récupère la liste des genres disponibles
     * 
     * @return array
     */
    public function getGenres()
    {
        $cacheKey = "game_genres";

        // Utiliser le cache en production
        if (app()->environment('production')) {
            return Cache::remember($cacheKey, 60 * 24 * 7, function () {
                return $this->makeRequest("/genres");
            });
        }

        // En développement, on désactive le cache pour faciliter le débogage
        return $this->makeRequest("/genres");
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

        $params['key'] = $this->apiKey;

        try {
            Log::info('Requête API RAWG', [
                'endpoint' => $endpoint,
                'params' => array_merge($params, ['key' => 'XXXX']), // Masquer la clé API dans les logs
            ]);

            // Définir un timeout pour éviter que l'application ne reste bloquée
            $response = Http::timeout(5)->get($this->baseUrl . $endpoint, $params);

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
