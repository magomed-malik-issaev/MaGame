@extends('layouts.app')

@section('title', 'Profil de '.$user->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne de gauche - Infos profil -->
        <div class="lg:col-span-1">
            <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
                <!-- En-tête du profil -->
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-700 mb-4">
                        @if ($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        @endif
                    </div>
                    <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                    @if($user->location)
                    <p class="text-gray-300 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $user->location }}
                    </p>
                    @endif
                    @if($user->favorite_platform)
                    <div class="bg-purple-700 text-white text-sm px-3 py-1 rounded-full mt-2">
                        {{ $user->favorite_platform }}
                    </div>
                    @endif
                </div>

                <!-- Bio -->
                @if($user->bio)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-white mb-2">Bio</h3>
                    <p class="text-gray-300">{{ $user->bio }}</p>
                </div>
                @endif

                <!-- Informations de contact -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-white mb-3">Comptes de jeu</h3>
                    <ul class="space-y-2">
                        @if($user->discord_username)
                        <li class="flex items-center text-gray-300">
                            <img src="https://cdn.jsdelivr.net/npm/simple-icons@v5/icons/discord.svg" class="h-5 w-5 mr-2 invert opacity-75">
                            <span>{{ $user->discord_username }}</span>
                        </li>
                        @endif

                        @if($user->psn_username)
                        <li class="flex items-center text-gray-300">
                            <img src="https://cdn.jsdelivr.net/npm/simple-icons@v5/icons/playstation.svg" class="h-5 w-5 mr-2 invert opacity-75">
                            <span>{{ $user->psn_username }}</span>
                        </li>
                        @endif

                        @if($user->xbox_username)
                        <li class="flex items-center text-gray-300">
                            <img src="https://cdn.jsdelivr.net/npm/simple-icons@v5/icons/xbox.svg" class="h-5 w-5 mr-2 invert opacity-75">
                            <span>{{ $user->xbox_username }}</span>
                        </li>
                        @endif

                        @if($user->steam_username)
                        <li class="flex items-center text-gray-300">
                            <img src="https://cdn.jsdelivr.net/npm/simple-icons@v5/icons/steam.svg" class="h-5 w-5 mr-2 invert opacity-75">
                            <span>{{ $user->steam_username }}</span>
                        </li>
                        @endif

                        @if($user->nintendo_username)
                        <li class="flex items-center text-gray-300">
                            <img src="https://cdn.jsdelivr.net/npm/simple-icons@v5/icons/nintendoswitch.svg" class="h-5 w-5 mr-2 invert opacity-75">
                            <span>{{ $user->nintendo_username }}</span>
                        </li>
                        @endif
                    </ul>

                    @if(!$user->discord_username && !$user->psn_username && !$user->xbox_username && !$user->steam_username && !$user->nintendo_username)
                    <p class="text-gray-500 text-sm italic">Aucun compte de jeu associé</p>
                    @endif
                </div>

                <!-- Membre depuis -->
                <div>
                    <p class="text-sm text-gray-400">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>
                </div>

                <!-- Bouton pour voir d'autres utilisateurs -->
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <a href="{{ route('users.index') }}" class="block w-full bg-gray-700 hover:bg-gray-600 text-white text-center py-2 px-4 rounded-md transition-colors">
                        <span>Découvrir d'autres joueurs</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenu principal - Jeux -->
        <div class="lg:col-span-2">
            <!-- Jeux favoris -->
            <div class="bg-gray-800 rounded-lg p-6 shadow-lg mb-8">
                <h2 class="text-xl font-bold text-white mb-4">Jeux favoris</h2>

                @if($favoriteGames->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($favoriteGames as $game)
                    <a href="{{ route('games.show', $game->game_api_id) }}" class="block">
                        <div class="rounded-lg overflow-hidden bg-gray-700 aspect-square">
                            @if($game->background_image)
                            <img src="{{ $game->background_image }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="mt-2">
                            <p class="text-sm font-medium text-white truncate">{{ $game->name }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-gray-400">Aucun jeu favori pour le moment.</p>
                @endif
            </div>

            <!-- Collection de jeux -->
            <div class="bg-gray-800 rounded-lg p-6 shadow-lg mb-8">
                <h2 class="text-xl font-bold text-white mb-4">Collection ({{ $games->count() }})</h2>

                @if($games->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($games as $game)
                    <a href="{{ route('games.show', $game->game_api_id) }}" class="block bg-gray-700 rounded-lg overflow-hidden hover:bg-gray-600 transition-colors">
                        <div class="h-32 bg-gray-700">
                            @if($game->background_image)
                            <img src="{{ $game->background_image }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium text-white truncate">{{ $game->name }}</p>
                                <span class="bg-gray-800 text-xs px-2 py-1 rounded text-white">{{ ucfirst($game->pivot->status) }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-gray-400">Aucun jeu dans la collection pour le moment.</p>
                @endif
            </div>

            <!-- Avis récents -->
            <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
                <h2 class="text-xl font-bold text-white mb-4">Avis récents</h2>

                @if($ratings->count() > 0)
                <div class="space-y-4">
                    @foreach($ratings as $rating)
                    <div class="bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="mr-3 w-12 h-12 rounded-md overflow-hidden bg-gray-800">
                                    @if($rating->game && $rating->game->background_image)
                                    <img src="{{ $rating->game->background_image }}" alt="{{ $rating->game->name }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <a href="{{ route('games.show', $rating->game->game_api_id) }}" class="text-white font-medium hover:text-purple-400">{{ $rating->game->name }}</a>
                            </div>
                            <div class="flex items-center bg-yellow-500 text-black font-bold px-2 py-1 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                {{ $rating->rating }}
                            </div>
                        </div>
                        @if($rating->comment)
                        <div class="mt-3 text-gray-300">
                            {{ $rating->comment }}
                        </div>
                        @endif
                        <div class="mt-2 text-xs text-gray-400">
                            {{ $rating->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-400">Aucun avis pour le moment.</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection