<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Services\RawgApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{
    protected $rawgApiService;

    public function __construct(RawgApiService $rawgApiService)
    {
        $this->rawgApiService = $rawgApiService;
        // Définir le middleware auth au niveau des routes plutôt qu'ici
    }

    /**
     * Affiche la liste des jeux populaires sur la page d'accueil
     */
    public function index()
    {
        try {
            // Test de l'API key
            $apiKey = config('services.rawg.key');

            // Afficher une page de debug pour voir si l'API key est bien configurée
            if (empty($apiKey)) {
                return view('debug', [
                    'error' => 'La clé API RAWG n\'est pas configurée dans le fichier .env',
                    'debug_info' => [
                        'API Key' => $apiKey,
                        'Services config' => config('services'),
                    ]
                ]);
            }

            $popularGames = $this->rawgApiService->getPopularGames();
            $newGames = $this->rawgApiService->getNewGames();

            return view('games.index', [
                'popularGames' => $popularGames['results'] ?? [],
                'newGames' => $newGames['results'] ?? [],
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur dans GameController@index: ' . $e->getMessage());

            return view('debug', [
                'error' => 'Une erreur est survenue lors de la récupération des jeux',
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'debug_info' => [
                    'API Key' => config('services.rawg.key'),
                ]
            ]);
        }
    }

    /**
     * Affiche les détails d'un jeu spécifique
     */
    public function show($id)
    {
        // Vérifie d'abord si le jeu existe déjà dans notre base de données
        $game = Game::where('game_api_id', $id)->first();

        // Si le jeu n'existe pas dans notre DB, récupérer les données de l'API
        if (!$game) {
            $gameData = $this->rawgApiService->getGameDetails($id);

            if (!$gameData) {
                abort(404, 'Jeu non trouvé');
            }

            // Enregistrer le jeu dans notre base de données
            $game = Game::create([
                'game_api_id' => $gameData['id'],
                'name' => $gameData['name'],
                'description' => $gameData['description'] ?? null,
                'background_image' => $gameData['background_image'] ?? null,
                'released' => $gameData['released'] ? Carbon::parse($gameData['released']) : null,
                'platforms' => isset($gameData['platforms']) ? json_encode(collect($gameData['platforms'])->pluck('platform.name')) : null,
                'genres' => isset($gameData['genres']) ? json_encode(collect($gameData['genres'])->pluck('name')) : null,
                'publishers' => isset($gameData['publishers']) ? json_encode(collect($gameData['publishers'])->pluck('name')) : null,
                'rating' => $gameData['rating'] ?? null
            ]);
        }

        // Récupérer les commentaires associés au jeu
        $comments = $game->comments()->with('user')->latest()->get();

        // Récupérer les détails complets du jeu depuis l'API pour toujours avoir les données à jour
        $gameDetails = $this->rawgApiService->getGameDetails($id);

        // Vérifier si l'utilisateur a déjà noté ce jeu
        $userRating = null;
        if (Auth::check()) {
            $userRating = Auth::user()->ratings()->where('game_id', $game->id)->first();
        }

        return view('games.show', [
            'game' => $game,
            'gameDetails' => $gameDetails,
            'comments' => $comments,
            'userRating' => $userRating
        ]);
    }

    /**
     * Recherche des jeux
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return redirect()->route('games.index');
        }

        $searchResults = $this->rawgApiService->searchGames($query);

        return view('games.search', [
            'games' => $searchResults['results'] ?? [],
            'query' => $query
        ]);
    }

    /**
     * Affiche la collection de jeux de l'utilisateur connecté
     */
    public function myGames()
    {
        $user = Auth::user();
        $games = $user->games()->with('ratings')->get();

        $favorites = $games->where('pivot.status', 'favori');
        $inProgress = $games->where('pivot.status', 'en_cours');
        $completed = $games->where('pivot.status', 'terminé');

        return view('games.my-games', [
            'favorites' => $favorites,
            'inProgress' => $inProgress,
            'completed' => $completed,
            'allGames' => $games
        ]);
    }

    /**
     * Ajoute un jeu à la collection de l'utilisateur
     */
    public function addToCollection(Request $request, $id)
    {
        $user = Auth::user();
        $game = Game::where('game_api_id', $id)->first();

        if (!$game) {
            // Récupérer les informations du jeu depuis l'API
            $gameData = $this->rawgApiService->getGameDetails($id);

            if (!$gameData) {
                return back()->with('error', 'Jeu non trouvé');
            }

            // Créer le jeu dans notre base de données
            $game = Game::create([
                'game_api_id' => $gameData['id'],
                'name' => $gameData['name'],
                'background_image' => $gameData['background_image'] ?? null,
                'released' => $gameData['released'] ? Carbon::parse($gameData['released']) : null,
            ]);
        }

        // Vérifier si le jeu est déjà dans la collection
        if ($user->games()->where('game_id', $game->id)->exists()) {
            // Mettre à jour le statut
            $user->games()->updateExistingPivot($game->id, [
                'status' => $request->input('status', 'favori')
            ]);
        } else {
            // Ajouter à la collection
            $user->games()->attach($game->id, [
                'status' => $request->input('status', 'favori')
            ]);
        }

        return back()->with('success', 'Jeu ajouté à votre collection');
    }

    /**
     * Supprime un jeu de la collection de l'utilisateur
     */
    public function removeFromCollection($id)
    {
        $user = Auth::user();
        $game = Game::where('game_api_id', $id)->first();

        if ($game) {
            $user->games()->detach($game->id);
        }

        return back()->with('success', 'Jeu retiré de votre collection');
    }

    /**
     * Affiche tous les jeux avec pagination
     */
    public function allGames(Request $request)
    {
        try {
            // Ajout d'un indicateur de limite d'API dans la vue
            $apiStats = $this->rawgApiService->getRequestStats();

            $page = $request->input('page', 1);
            $pageSize = 20; // Nombre de jeux par page

            // Récupérer les filtres
            $filters = [];

            // Filtre par genres
            if ($request->has('genres') && !empty($request->genres)) {
                $filters['genres'] = $request->genres;
            }

            // Filtre par plateformes
            if ($request->has('platforms') && !empty($request->platforms)) {
                $filters['platforms'] = $request->platforms;
            }

            // Filtre par date de sortie
            if ($request->has('dates') && !empty($request->dates)) {
                $filters['dates'] = $request->dates;
            }

            // Tri
            if ($request->has('ordering') && !empty($request->ordering)) {
                $filters['ordering'] = $request->ordering;
            }

            // Récupérer les genres pour le filtre
            $genres = $this->rawgApiService->getGenres();

            // Debug - Log les genres récupérés
            Log::info('Genres récupérés pour les filtres : ', [
                'count' => isset($genres['results']) ? count($genres['results']) : 0,
                'genres' => $genres['results'] ?? []
            ]);

            $gamesData = $this->rawgApiService->getAllGames($page, $pageSize, $filters);

            return view('games.all', [
                'games' => $gamesData['results'] ?? [],
                'currentPage' => $page,
                'nextPage' => isset($gamesData['next']) ? $page + 1 : null,
                'prevPage' => $page > 1 ? $page - 1 : null,
                'totalGames' => $gamesData['count'] ?? 0,
                'genres' => $genres['results'] ?? [],
                'selectedGenres' => $request->genres ?? [],
                'selectedPlatforms' => $request->platforms ?? [],
                'selectedDates' => $request->dates ?? '',
                'selectedOrdering' => $request->ordering ?? '',
                'apiStats' => $apiStats,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur dans GameController@allGames: ' . $e->getMessage());

            return view('debug', [
                'error' => 'Une erreur est survenue lors de la récupération de tous les jeux',
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Affiche l'état d'utilisation de l'API RAWG
     */
    public function apiStatus()
    {
        $apiStats = $this->rawgApiService->getRequestStats();

        return view('admin.api-status', [
            'apiStats' => $apiStats
        ]);
    }
}
