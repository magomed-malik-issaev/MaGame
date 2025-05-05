@extends('layouts.app')

@section('title', $game->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header du jeu avec image de fond -->
    <div class="relative rounded-xl overflow-hidden mb-10 h-80">
        <div class="absolute inset-0">
            <img src="{{ $game->background_image }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/70 to-transparent"></div>
        </div>
        <div class="relative z-10 h-full flex flex-col justify-end p-6">
            <div class="flex flex-wrap items-center gap-4 mb-2">
                @if($gameDetails['released'] ?? false)
                <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-md text-sm">
                    {{ \Carbon\Carbon::parse($gameDetails['released'])->format('d/m/Y') }}
                </span>
                @endif

                @if(!empty($game->genres))
                @foreach(json_decode($game->genres) as $genre)
                <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-md text-sm">{{ $genre }}</span>
                @endforeach
                @endif

                <div class="flex items-center bg-gray-800 px-3 py-1 rounded-md">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span class="ml-1 text-white font-medium">{{ $game->rating }}</span>
                </div>
            </div>

            <h1 class="text-4xl font-bold text-white mb-2">{{ $game->name }}</h1>

            @if(!empty($game->platforms))
            <div class="flex flex-wrap gap-2">
                @foreach(json_decode($game->platforms) as $platform)
                <span class="text-gray-400 text-sm">{{ $platform }}</span>
                @if(!$loop->last) <span class="text-gray-600">•</span> @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale avec description et screenshots -->
        <div class="lg:col-span-2">
            <!-- Description -->
            <div class="bg-gray-800 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-white mb-4">À propos</h2>
                <div class="prose prose-invert max-w-none">
                    {!! $game->description !!}
                </div>
            </div>

            <!-- Screenshots -->
            @if(isset($gameDetails['screenshots']) && count($gameDetails['screenshots']) > 0)
            <div class="bg-gray-800 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-white mb-4">Screenshots</h2>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($gameDetails['screenshots'] as $screenshot)
                    <div class="rounded-md overflow-hidden">
                        <img src="{{ $screenshot['image'] }}" alt="Screenshot" class="w-full h-auto object-cover">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Commentaires -->
            <div class="bg-gray-800 rounded-lg p-6">
                <h2 class="text-xl font-bold text-white mb-4">Commentaires ({{ $comments->count() }})</h2>

                @auth
                <form action="{{ route('comments.store', $game->id) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="mb-4">
                        <textarea name="content" rows="3" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Partagez votre avis sur ce jeu..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                            Publier
                        </button>
                    </div>
                </form>
                @else
                <div class="bg-gray-700 rounded-lg p-4 mb-6 text-center">
                    <p class="text-gray-300">
                        <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300">Connectez-vous</a>
                        pour laisser un commentaire.
                    </p>
                </div>
                @endauth

                <div class="space-y-4">
                    @forelse($comments as $comment)
                    <div class="bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center">
                                <div class="font-medium text-white">{{ $comment->user->name }}</div>
                                <span class="text-xs text-gray-400 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>

                            @if(Auth::check() && Auth::id() == $comment->user_id)
                            <div class="flex space-x-2">
                                <button class="text-gray-400 hover:text-white edit-comment" data-id="{{ $comment->id }}" data-content="{{ $comment->content }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        <p class="text-gray-200">{{ $comment->content }}</p>
                    </div>
                    @empty
                    <div class="text-center text-gray-400">
                        Aucun commentaire pour le moment. Soyez le premier à donner votre avis !
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar avec infos et actions -->
        <div>
            <!-- Actions -->
            <div class="bg-gray-800 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-white mb-4">Actions</h2>

                @auth
                <div class="space-y-3">
                    <!-- Notation -->
                    <div class="mb-4">
                        <h3 class="text-gray-300 text-sm mb-2">Noter ce jeu :</h3>
                        <form action="{{ route('ratings.store', $game->id) }}" method="POST" class="flex items-center">
                            @csrf
                            <div class="flex space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="submit" name="rating" value="{{ $i }}" class="focus:outline-none">
                                    <svg class="w-8 h-8 {{ $userRating && $userRating->rating >= $i ? 'text-yellow-400' : 'text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    </button>
                                    @endfor
                            </div>
                        </form>
                    </div>

                    <!-- Ajout à la collection -->
                    <div>
                        <form action="{{ route('games.addToCollection', $game->id) }}" method="POST">
                            @csrf
                            <select name="status" class="bg-gray-700 text-white rounded-l-md border-0 p-2 focus:ring-2 focus:ring-purple-500">
                                <option value="favori">Favoris</option>
                                <option value="en_cours">En cours</option>
                                <option value="terminé">Terminé</option>
                            </select>
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-r-md transition-colors">
                                Ajouter
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="bg-gray-700 rounded-lg p-4 text-center">
                    <p class="text-gray-300 mb-3">
                        Connectez-vous pour noter ce jeu et l'ajouter à votre collection.
                    </p>
                    <a href="{{ route('login') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md transition-colors inline-block">
                        Se connecter
                    </a>
                </div>
                @endauth
            </div>

            <!-- Informations -->
            <div class="bg-gray-800 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-white mb-4">Informations</h2>

                <div class="space-y-3">
                    @if($gameDetails['publishers'] ?? false)
                    <div>
                        <h3 class="text-gray-400 text-sm">Éditeurs :</h3>
                        <div class="text-white">
                            @foreach($gameDetails['publishers'] as $publisher)
                            {{ $publisher['name'] }}@if(!$loop->last),@endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($gameDetails['developers'] ?? false)
                    <div>
                        <h3 class="text-gray-400 text-sm">Développeurs :</h3>
                        <div class="text-white">
                            @foreach($gameDetails['developers'] as $developer)
                            {{ $developer['name'] }}@if(!$loop->last),@endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($gameDetails['esrb_rating'] ?? false)
                    <div>
                        <h3 class="text-gray-400 text-sm">Classification :</h3>
                        <span class="inline-block bg-gray-700 text-white px-2 py-1 rounded text-sm">
                            {{ $gameDetails['esrb_rating']['name'] }}
                        </span>
                    </div>
                    @endif

                    @if($gameDetails['website'] ?? false)
                    <div>
                        <h3 class="text-gray-400 text-sm">Site web :</h3>
                        <a href="{{ $gameDetails['website'] }}" target="_blank" class="text-purple-400 hover:text-purple-300">
                            {{ $gameDetails['website'] }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection