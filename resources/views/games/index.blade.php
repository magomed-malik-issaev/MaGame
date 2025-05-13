@extends('layouts.app')

@section('title', 'Découvrez les meilleurs jeux')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Bannière d'accueil -->
    <div class="relative rounded-xl overflow-hidden mb-10 h-80">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Gaming Banner" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900 to-transparent"></div>
        </div>
        <div class="relative z-10 h-full flex flex-col justify-center px-10">
            <h1 class="text-4xl font-bold text-white mb-3">Bienvenue sur MyGame</h1>
            <p class="text-xl text-gray-200 mb-6 max-w-2xl">Découvrez, notez et collectionnez vos jeux vidéo préférés avec notre plateforme alimentée par RAWG.</p>
            <div>
                <a href="{{ route('register') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-6 rounded-md transition-colors">
                    Commencer maintenant
                </a>
            </div>
        </div>
    </div>

    <!-- Jeux populaires -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white">Jeux populaires</h2>
            <a href="{{ route('games.all') }}" class="text-purple-400 hover:text-purple-300 text-sm">Voir tout</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($popularGames as $game)
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                <a href="{{ route('games.show', $game['id']) }}">
                    <div class="w-full h-48 bg-gray-700 relative">
                        @if(isset($game['background_image']) && !empty($game['background_image']))
                        <img src="{{ $game['background_image'] }}" alt="{{ $game['name'] }}" class="w-full h-full object-cover">
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
                        <a href="{{ route('games.show', $game['id']) }}" class="text-lg font-semibold text-white hover:text-purple-400 truncate">{{ $game['name'] }}</a>
                        <div class="flex items-center bg-gray-700 px-2 py-1 rounded">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="ml-1 text-sm text-white">{{ $game['rating'] ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-1 mt-2 mb-3">
                        @foreach($game['genres'] ?? [] as $genre)
                        <span class="bg-gray-700 text-xs px-2 py-1 rounded text-gray-300">{{ $genre['name'] }}</span>
                        @endforeach
                    </div>

                    <div class="mt-4 flex space-x-2">
                        @auth
                        <form action="{{ route('games.addToCollection', $game['id']) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="favori">
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-xs px-3 py-1 rounded-md transition-colors">
                                Ajouter
                            </button>
                        </form>
                        @endauth
                        <a href="{{ route('games.show', $game['id']) }}" class="bg-gray-700 hover:bg-gray-600 text-white text-xs px-3 py-1 rounded-md transition-colors">
                            Détails
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Nouveaux jeux -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white">Nouveaux jeux</h2>
            <a href="#" class="text-purple-400 hover:text-purple-300 text-sm">Voir tout</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($newGames as $game)
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                <a href="{{ route('games.show', $game['id']) }}">
                    <div class="w-full h-48 bg-gray-700 relative">
                        @if(isset($game['background_image']) && !empty($game['background_image']))
                        <img src="{{ $game['background_image'] }}" alt="{{ $game['name'] }}" class="w-full h-full object-cover">
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
                        <a href="{{ route('games.show', $game['id']) }}" class="text-lg font-semibold text-white hover:text-purple-400 truncate">{{ $game['name'] }}</a>
                        <div class="flex items-center bg-gray-700 px-2 py-1 rounded">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="ml-1 text-sm text-white">{{ $game['rating'] ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-1 mt-2 mb-3">
                        @foreach($game['genres'] ?? [] as $genre)
                        <span class="bg-gray-700 text-xs px-2 py-1 rounded text-gray-300">{{ $genre['name'] }}</span>
                        @endforeach
                    </div>

                    <div class="mt-4 flex space-x-2">
                        @auth
                        <form action="{{ route('games.addToCollection', $game['id']) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="favori">
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-xs px-3 py-1 rounded-md transition-colors">
                                Ajouter
                            </button>
                        </form>
                        @endauth
                        <a href="{{ route('games.show', $game['id']) }}" class="bg-gray-700 hover:bg-gray-600 text-white text-xs px-3 py-1 rounded-md transition-colors">
                            Détails
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection