@extends('layouts.app')

@section('title', 'Recherche : ' . $query)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-3">Résultats de recherche pour "{{ $query }}"</h1>
        <div class="text-gray-400">{{ count($games) }} résultat(s) trouvé(s)</div>
    </div>

    @if(count($games) > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($games as $game)
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
                        <span class="ml-1 text-sm text-white">{{ $game['rating'] ?? '0' }}</span>
                    </div>
                </div>

                @if(isset($game['released']))
                <div class="text-sm text-gray-400 mb-2">
                    Sortie : {{ \Carbon\Carbon::parse($game['released'])->format('d/m/Y') }}
                </div>
                @endif

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
    @else
    <div class="bg-gray-800 rounded-lg p-8 text-center">
        <div class="text-gray-400 mb-4">
            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h2 class="text-xl font-semibold text-white mb-2">Aucun résultat trouvé</h2>
            <p>Nous n'avons pas trouvé de jeux correspondant à "{{ $query }}".</p>
        </div>
        <a href="{{ route('games.index') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
            Retour à l'accueil
        </a>
    </div>
    @endif
</div>
@endsection