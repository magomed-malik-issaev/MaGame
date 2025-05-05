@extends('layouts.app')

@section('title', 'Ma collection de jeux')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-white mb-8">Ma collection de jeux</h1>

    <!-- Onglets de catégories -->
    <div class="border-b border-gray-700 mb-6">
        <div class="flex -mb-px space-x-8">
            <button class="category-tab text-purple-500 border-b-2 border-purple-500 pb-2 px-1 font-medium" data-target="all">
                Tous ({{ $allGames->count() }})
            </button>
            <button class="category-tab text-gray-400 hover:text-white pb-2 px-1 font-medium" data-target="favorites">
                Favoris ({{ $favorites->count() }})
            </button>
            <button class="category-tab text-gray-400 hover:text-white pb-2 px-1 font-medium" data-target="in-progress">
                En cours ({{ $inProgress->count() }})
            </button>
            <button class="category-tab text-gray-400 hover:text-white pb-2 px-1 font-medium" data-target="completed">
                Terminés ({{ $completed->count() }})
            </button>
        </div>
    </div>

    <!-- Liste de tous les jeux -->
    <div id="all" class="category-content">
        @if($allGames->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($allGames as $game)
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                <a href="{{ route('games.show', $game->game_api_id) }}">
                    <div class="w-full h-48 bg-gray-700 relative">
                        @if(isset($game->background_image) && !empty($game->background_image))
                        <img src="{{ $game->background_image }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="flex items-center justify-center w-full h-full bg-gray-700 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                    </div>
                </a>
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <a href="{{ route('games.show', $game->game_api_id) }}" class="text-lg font-semibold text-white hover:text-purple-400 truncate">{{ $game->name }}</a>
                        <div class="flex items-center bg-gray-700 px-2 py-1 rounded">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="ml-1 text-sm text-white">{{ $game->rating ?? '0' }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <span class="inline-block bg-purple-600 text-xs px-2 py-1 rounded text-white">{{ ucfirst($game->pivot->status) }}</span>
                    </div>

                    <div class="mt-4 flex space-x-2">
                        <form action="{{ route('games.removeFromCollection', $game->game_api_id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded-md transition-colors">
                                Retirer
                            </button>
                        </form>
                        <form action="{{ route('games.addToCollection', $game->game_api_id) }}" method="POST" class="inline flex-1">
                            @csrf
                            <select name="status" class="bg-gray-700 text-white text-xs rounded-l-md border-0 py-1 px-2 focus:ring-2 focus:ring-purple-500 w-2/3">
                                <option value="favori" {{ $game->pivot->status == 'favori' ? 'selected' : '' }}>Favoris</option>
                                <option value="en_cours" {{ $game->pivot->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="terminé" {{ $game->pivot->status == 'terminé' ? 'selected' : '' }}>Terminé</option>
                            </select>
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-xs px-3 py-1 rounded-r-md transition-colors">
                                Changer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-800 rounded-lg p-8 text-center">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h2 class="text-xl font-semibold text-white mb-2">Votre collection est vide</h2>
                <p>Parcourez notre bibliothèque et ajoutez des jeux à votre collection.</p>
            </div>
            <a href="{{ route('games.index') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                Découvrir des jeux
            </a>
        </div>
        @endif
    </div>

    <!-- Liste des favoris -->
    <div id="favorites" class="category-content hidden">
        @if($favorites->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($favorites as $game)
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                <a href="{{ route('games.show', $game->game_api_id) }}">
                    <div class="w-full h-48 bg-gray-700 relative">
                        @if(isset($game->background_image) && !empty($game->background_image))
                        <img src="{{ $game->background_image }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="flex items-center justify-center w-full h-full bg-gray-700 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                    </div>
                </a>
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <a href="{{ route('games.show', $game->game_api_id) }}" class="text-lg font-semibold text-white hover:text-purple-400 truncate">{{ $game->name }}</a>
                        <div class="flex items-center bg-gray-700 px-2 py-1 rounded">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="ml-1 text-sm text-white">{{ $game->rating ?? '0' }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex space-x-2">
                        <form action="{{ route('games.removeFromCollection', $game->game_api_id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded-md transition-colors">
                                Retirer
                            </button>
                        </form>
                        <form action="{{ route('games.addToCollection', $game->game_api_id) }}" method="POST" class="inline flex-1">
                            @csrf
                            <select name="status" class="bg-gray-700 text-white text-xs rounded-l-md border-0 py-1 px-2 focus:ring-2 focus:ring-purple-500 w-2/3">
                                <option value="favori" selected>Favoris</option>
                                <option value="en_cours">En cours</option>
                                <option value="terminé">Terminé</option>
                            </select>
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-xs px-3 py-1 rounded-r-md transition-colors">
                                Changer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-800 rounded-lg p-8 text-center">
            <div class="text-gray-400">
                <h2 class="text-xl font-semibold text-white mb-2">Vous n'avez pas encore de favoris</h2>
                <p>Parcourez notre bibliothèque et ajoutez des jeux à vos favoris.</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Liste des jeux en cours -->
    <div id="in-progress" class="category-content hidden">
        @if($inProgress->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($inProgress as $game)
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                <a href="{{ route('games.show', $game->game_api_id) }}">
                    <div class="w-full h-48 bg-gray-700 relative">
                        @if(isset($game->background_image) && !empty($game->background_image))
                        <img src="{{ $game->background_image }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="flex items-center justify-center w-full h-full bg-gray-700 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                    </div>
                </a>
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <a href="{{ route('games.show', $game->game_api_id) }}" class="text-lg font-semibold text-white hover:text-purple-400 truncate">{{ $game->name }}</a>
                        <div class="flex items-center bg-gray-700 px-2 py-1 rounded">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="ml-1 text-sm text-white">{{ $game->rating ?? '0' }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex space-x-2">
                        <form action="{{ route('games.removeFromCollection', $game->game_api_id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded-md transition-colors">
                                Retirer
                            </button>
                        </form>
                        <form action="{{ route('games.addToCollection', $game->game_api_id) }}" method="POST" class="inline flex-1">
                            @csrf
                            <select name="status" class="bg-gray-700 text-white text-xs rounded-l-md border-0 py-1 px-2 focus:ring-2 focus:ring-purple-500 w-2/3">
                                <option value="favori">Favoris</option>
                                <option value="en_cours" selected>En cours</option>
                                <option value="terminé">Terminé</option>
                            </select>
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-xs px-3 py-1 rounded-r-md transition-colors">
                                Changer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-800 rounded-lg p-8 text-center">
            <div class="text-gray-400">
                <h2 class="text-xl font-semibold text-white mb-2">Vous n'avez pas de jeux en cours</h2>
                <p>Commencez à jouer et marquez vos jeux comme "en cours".</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Liste des jeux terminés -->
    <div id="completed" class="category-content hidden">
        @if($completed->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($completed as $game)
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                <a href="{{ route('games.show', $game->game_api_id) }}">
                    <div class="w-full h-48 bg-gray-700 relative">
                        @if(isset($game->background_image) && !empty($game->background_image))
                        <img src="{{ $game->background_image }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="flex items-center justify-center w-full h-full bg-gray-700 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                    </div>
                </a>
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <a href="{{ route('games.show', $game->game_api_id) }}" class="text-lg font-semibold text-white hover:text-purple-400 truncate">{{ $game->name }}</a>
                        <div class="flex items-center bg-gray-700 px-2 py-1 rounded">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="ml-1 text-sm text-white">{{ $game->rating ?? '0' }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex space-x-2">
                        <form action="{{ route('games.removeFromCollection', $game->game_api_id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded-md transition-colors">
                                Retirer
                            </button>
                        </form>
                        <form action="{{ route('games.addToCollection', $game->game_api_id) }}" method="POST" class="inline flex-1">
                            @csrf
                            <select name="status" class="bg-gray-700 text-white text-xs rounded-l-md border-0 py-1 px-2 focus:ring-2 focus:ring-purple-500 w-2/3">
                                <option value="favori">Favoris</option>
                                <option value="en_cours">En cours</option>
                                <option value="terminé" selected>Terminé</option>
                            </select>
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-xs px-3 py-1 rounded-r-md transition-colors">
                                Changer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-800 rounded-lg p-8 text-center">
            <div class="text-gray-400">
                <h2 class="text-xl font-semibold text-white mb-2">Vous n'avez pas de jeux terminés</h2>
                <p>Lorsque vous terminez un jeu, marquez-le comme "terminé".</p>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.category-tab');
        const contents = document.querySelectorAll('.category-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Cacher tous les contenus
                contents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Réinitialiser l'apparence de tous les onglets
                tabs.forEach(t => {
                    t.classList.remove('text-purple-500', 'border-b-2', 'border-purple-500');
                    t.classList.add('text-gray-400', 'hover:text-white');
                });

                // Afficher le contenu correspondant à l'onglet cliqué
                const target = this.getAttribute('data-target');
                document.getElementById(target).classList.remove('hidden');

                // Mettre en évidence l'onglet actif
                this.classList.remove('text-gray-400', 'hover:text-white');
                this.classList.add('text-purple-500', 'border-b-2', 'border-purple-500');
            });
        });
    });
</script>
@endsection