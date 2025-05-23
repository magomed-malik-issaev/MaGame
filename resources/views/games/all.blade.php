@extends('layouts.app')

@section('title', 'Tous les jeux')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-white">Catalogue complet de jeux</h1>
        <div class="text-gray-400">{{ $totalGames }} jeux au total</div>
    </div>

    <!-- DEBUG INFO -->
    <div class="bg-gray-900 p-4 mb-4 text-white rounded">
        Nombre de genres disponibles : {{ isset($genres) ? count($genres) : 0 }}

        @if(isset($apiStats))
        <div class="mt-2 text-sm">
            <div class="flex items-center">
                <span class="mr-2">Utilisation API aujourd'hui:</span>
                <div class="w-32 bg-gray-700 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full 
                        @if($apiStats['percentage'] < 50) 
                            bg-green-500
                        @elseif($apiStats['percentage'] < 80)
                            bg-yellow-500
                        @else
                            bg-red-500
                        @endif"
                        style="width: {{ min($apiStats['percentage'], 100) }}%">
                    </div>
                </div>
                <span class="ml-2">{{ $apiStats['count'] }}/{{ $apiStats['limit'] }}</span>
            </div>
        </div>
        @endif
    </div>

    <!-- Section filtres -->
    <div class="bg-gray-800 p-4 rounded-lg mb-8">
        <form action="{{ route('games.all') }}" method="GET" class="space-y-4">
            <div class="flex flex-wrap -mx-2">
                <!-- Filtre par genre - Simplifié avec options statiques -->
                <div class="px-2 w-full md:w-1/3 mb-4">
                    <label for="genres" class="block text-sm font-medium text-gray-300 mb-1">Genres</label>
                    <select name="genres" id="genres" class="bg-gray-700 text-white rounded-md w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Tous les genres</option>
                        <option value="4">Action</option>
                        <option value="51">Indie</option>
                        <option value="3">Adventure</option>
                        <option value="5">RPG</option>
                        <option value="2">Shooter</option>
                        <option value="10">Strategy</option>
                    </select>
                </div>

                <!-- Filtre par date -->
                <div class="px-2 w-full md:w-1/3 mb-4">
                    <label for="dates" class="block text-sm font-medium text-gray-300 mb-1">Période de sortie</label>
                    <select name="dates" id="dates" class="bg-gray-700 text-white rounded-md w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Toutes les dates</option>
                        <option value="2023-01-01,2023-12-31" {{ isset($selectedDates) && $selectedDates == '2023-01-01,2023-12-31' ? 'selected' : '' }}>2023</option>
                        <option value="2022-01-01,2022-12-31" {{ isset($selectedDates) && $selectedDates == '2022-01-01,2022-12-31' ? 'selected' : '' }}>2022</option>
                        <option value="2021-01-01,2021-12-31" {{ isset($selectedDates) && $selectedDates == '2021-01-01,2021-12-31' ? 'selected' : '' }}>2021</option>
                        <option value="2020-01-01,2020-12-31" {{ isset($selectedDates) && $selectedDates == '2020-01-01,2020-12-31' ? 'selected' : '' }}>2020</option>
                        <option value="2015-01-01,2019-12-31" {{ isset($selectedDates) && $selectedDates == '2015-01-01,2019-12-31' ? 'selected' : '' }}>2015-2019</option>
                        <option value="2010-01-01,2014-12-31" {{ isset($selectedDates) && $selectedDates == '2010-01-01,2014-12-31' ? 'selected' : '' }}>2010-2014</option>
                        <option value="2000-01-01,2009-12-31" {{ isset($selectedDates) && $selectedDates == '2000-01-01,2009-12-31' ? 'selected' : '' }}>2000-2009</option>
                        <option value="1990-01-01,1999-12-31" {{ isset($selectedDates) && $selectedDates == '1990-01-01,1999-12-31' ? 'selected' : '' }}>1990-1999</option>
                    </select>
                </div>

                <!-- Filtre par tri -->
                <div class="px-2 w-full md:w-1/3 mb-4">
                    <label for="ordering" class="block text-sm font-medium text-gray-300 mb-1">Trier par</label>
                    <select name="ordering" id="ordering" class="bg-gray-700 text-white rounded-md w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="-added" {{ isset($selectedOrdering) && $selectedOrdering == '-added' ? 'selected' : '' }}>Popularité</option>
                        <option value="-released" {{ isset($selectedOrdering) && $selectedOrdering == '-released' ? 'selected' : '' }}>Date de sortie</option>
                        <option value="name" {{ isset($selectedOrdering) && $selectedOrdering == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                        <option value="-name" {{ isset($selectedOrdering) && $selectedOrdering == '-name' ? 'selected' : '' }}>Nom (Z-A)</option>
                        <option value="-rating" {{ isset($selectedOrdering) && $selectedOrdering == '-rating' ? 'selected' : '' }}>Note</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md transition-colors">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
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
                        <span class="ml-1 text-sm text-white">{{ $game['rating'] ?? 'N/A' }}</span>
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

    <!-- Pagination -->
    <div class="flex justify-between items-center py-6 border-t border-gray-700">
        <div>
            @if($prevPage)
            <a href="{{ route('games.all', array_merge(['page' => $prevPage], request()->only(['genres', 'dates', 'ordering']))) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                &larr; Page précédente
            </a>
            @else
            <span class="bg-gray-800 text-gray-500 cursor-not-allowed px-4 py-2 rounded-md">
                &larr; Page précédente
            </span>
            @endif
        </div>

        <div class="text-gray-400">
            Page {{ $currentPage }}
        </div>

        <div>
            @if($nextPage)
            <a href="{{ route('games.all', array_merge(['page' => $nextPage], request()->only(['genres', 'dates', 'ordering']))) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                Page suivante &rarr;
            </a>
            @else
            <span class="bg-gray-800 text-gray-500 cursor-not-allowed px-4 py-2 rounded-md">
                Page suivante &rarr;
            </span>
            @endif
        </div>
    </div>
</div>
@endsection