<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de débogage - MaGame</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-white p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-red-500 mb-4">⚠️ Page de débogage</h1>

        @if(isset($error))
        <div class="bg-red-900 border border-red-700 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-red-300 mb-2">Erreur :</h2>
            <p class="text-white mb-4">{{ $error }}</p>

            @if(isset($exception))
            <div class="bg-gray-800 p-4 rounded overflow-auto">
                <h3 class="text-lg font-bold text-gray-300 mb-2">Exception :</h3>
                <p class="text-gray-300 mb-2">{{ $exception }}</p>

                @if(isset($trace))
                <div class="mt-4">
                    <h3 class="text-lg font-bold text-gray-300 mb-2">Trace :</h3>
                    <pre class="text-xs text-gray-400 overflow-auto p-2">{{ $trace }}</pre>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif

        @if(isset($debug_info))
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-300 mb-4">Informations de débogage :</h2>

            <div class="space-y-4">
                @foreach($debug_info as $key => $value)
                <div>
                    <h3 class="text-lg font-semibold text-gray-300">{{ $key }} :</h3>
                    <div class="bg-gray-900 rounded p-3 mt-1">
                        @if(is_array($value) || is_object($value))
                        <pre class="text-xs text-gray-400 overflow-auto p-2">{{ print_r($value, true) }}</pre>
                        @else
                        <p class="text-gray-400">{{ $value ?: 'Non défini' }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-8 text-center">
            <a href="/" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors inline-block">
                Retour à l'accueil
            </a>
        </div>
    </div>
</body>

</html>