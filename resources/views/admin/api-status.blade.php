@extends('layouts.app')

@section('title', 'Statistiques d\'API RAWG')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-8">
        <h1 class="text-3xl font-bold text-white mb-6">Statistiques d'API RAWG</h1>

        <div class="bg-gray-800 rounded-lg p-6">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-white mb-3">Utilisation aujourd'hui ({{ $apiStats['date'] }})</h2>

                <div class="flex items-center mb-2">
                    <div class="w-full bg-gray-700 rounded-full h-4 mr-4">
                        <div class="h-4 rounded-full 
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
                    <span class="text-white font-medium w-32">{{ $apiStats['percentage'] }}%</span>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-4">
                    <div class="bg-gray-700 p-4 rounded-lg">
                        <div class="text-gray-400 text-sm mb-1">Requêtes effectuées</div>
                        <div class="text-2xl font-bold text-white">{{ number_format($apiStats['count']) }}</div>
                    </div>

                    <div class="bg-gray-700 p-4 rounded-lg">
                        <div class="text-gray-400 text-sm mb-1">Limite quotidienne</div>
                        <div class="text-2xl font-bold text-white">{{ number_format($apiStats['limit']) }}</div>
                    </div>

                    <div class="bg-gray-700 p-4 rounded-lg">
                        <div class="text-gray-400 text-sm mb-1">Requêtes restantes</div>
                        <div class="text-2xl font-bold 
                            @if($apiStats['remaining'] > $apiStats['limit'] * 0.5) 
                                text-green-500
                            @elseif($apiStats['remaining'] > $apiStats['limit'] * 0.2)
                                text-yellow-500
                            @else
                                text-red-500
                            @endif">
                            {{ number_format($apiStats['remaining']) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h2 class="text-xl font-semibold text-white mb-3">Recommandations</h2>

                <div class="bg-gray-700 p-4 rounded-lg text-white">
                    @if($apiStats['percentage'] < 50)
                        <p class="mb-2">✅ L'utilisation de l'API est à un niveau sain.</p>
                        <p>Continuez à utiliser le cache efficacement pour économiser des appels API.</p>
                        @elseif($apiStats['percentage'] < 80)
                            <p class="mb-2">⚠️ L'utilisation de l'API atteint un niveau modéré.</p>
                            <p>Envisagez d'augmenter la durée du cache ou de limiter certaines fonctionnalités.</p>
                            @else
                            <p class="mb-2">🚨 L'utilisation de l'API est élevée !</p>
                            <p>Il est fortement recommandé de :</p>
                            <ul class="list-disc pl-5 mt-2">
                                <li>Augmenter la durée du cache</li>
                                <li>Limiter temporairement les fonctionnalités non essentielles</li>
                                <li>Envisager un plan API supérieur si ce niveau d'utilisation est normal</li>
                            </ul>
                            @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection