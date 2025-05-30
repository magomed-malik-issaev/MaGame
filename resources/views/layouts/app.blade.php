<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MyGame') }} - @yield('title', 'Plateforme de jeux vidéo')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-900 text-white">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-gray-800 border-b border-gray-700" x-data="{ open: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center">
                                <img src="{{ asset('images/logomygame.png') }}" style="border-radius: 13%;" alt="MyGame Logo" class="h-10">
                            </a>
                        </div>

                        <!-- Navigation Links (Desktop) -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('games.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('games.index') ? 'border-purple-500 text-white' : 'border-transparent text-gray-300 hover:text-white hover:border-purple-500' }} focus:outline-none focus:text-white focus:border-purple-500 transition duration-150 ease-in-out">
                                Jeux populaires
                            </a>
                            <a href="{{ route('games.all') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('games.all') ? 'border-purple-500 text-white' : 'border-transparent text-gray-300 hover:text-white hover:border-purple-500' }} focus:outline-none focus:text-white focus:border-purple-500 transition duration-150 ease-in-out">
                                Catalogue complet
                            </a>
                            @auth
                            <a href="{{ route('games.myGames') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('games.myGames') ? 'border-purple-500 text-white' : 'border-transparent text-gray-300 hover:text-white hover:border-purple-500' }} focus:outline-none focus:text-white focus:border-purple-500 transition duration-150 ease-in-out">
                                Ma Collection
                            </a>
                            @endauth
                            <a href="{{ route('about') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('about') ? 'border-purple-500 text-white' : 'border-transparent text-gray-300 hover:text-white hover:border-purple-500' }} focus:outline-none focus:text-white focus:border-purple-500 transition duration-150 ease-in-out">
                                À propos
                            </a>
                        </div>
                    </div>

                    <!-- Search and User Menu (Desktop) -->
                    <div class="hidden sm:flex items-center">
                        <form action="{{ route('games.search') }}" method="GET" class="mr-4">
                            <div class="relative">
                                <input type="text" name="query" placeholder="Rechercher un jeu..." class="bg-gray-700 text-white rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <button type="submit" class="absolute right-0 top-0 bottom-0 px-3 text-gray-400 hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>

                        <!-- User Menu -->
                        <div class="ml-3 relative">
                            @auth
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('profile.edit') }}" class="text-sm text-gray-300 hover:text-white">
                                    {{ Auth::user()->name }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-300 hover:text-white">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                            @else
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white">Connexion</a>
                                <a href="{{ route('register') }}" class="text-sm text-gray-300 hover:text-white">Inscription</a>
                            </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center sm:hidden">
                        <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('games.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('games.index') ? 'border-purple-500 text-white bg-gray-700' : 'border-transparent text-gray-300 hover:text-white hover:bg-gray-700 hover:border-purple-500' }} focus:outline-none focus:text-white focus:bg-gray-700 focus:border-purple-500 transition duration-150 ease-in-out">
                        Jeux populaires
                    </a>
                    <a href="{{ route('games.all') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('games.all') ? 'border-purple-500 text-white bg-gray-700' : 'border-transparent text-gray-300 hover:text-white hover:bg-gray-700 hover:border-purple-500' }} focus:outline-none focus:text-white focus:bg-gray-700 focus:border-purple-500 transition duration-150 ease-in-out">
                        Catalogue complet
                    </a>
                    @auth
                    <a href="{{ route('games.myGames') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('games.myGames') ? 'border-purple-500 text-white bg-gray-700' : 'border-transparent text-gray-300 hover:text-white hover:bg-gray-700 hover:border-purple-500' }} focus:outline-none focus:text-white focus:bg-gray-700 focus:border-purple-500 transition duration-150 ease-in-out">
                        Ma Collection
                    </a>
                    @endauth
                    <a href="{{ route('about') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('about') ? 'border-purple-500 text-white bg-gray-700' : 'border-transparent text-gray-300 hover:text-white hover:bg-gray-700 hover:border-purple-500' }} focus:outline-none focus:text-white focus:bg-gray-700 focus:border-purple-500 transition duration-150 ease-in-out">
                        À propos
                    </a>
                </div>

                <!-- Mobile Search -->
                <div class="pt-4 pb-3 border-t border-gray-700">
                    <form action="{{ route('games.search') }}" method="GET" class="px-4">
                        <div class="relative">
                            <input type="text" name="query" placeholder="Rechercher un jeu..." class="bg-gray-700 text-white rounded-full px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <button type="submit" class="absolute right-0 top-0 bottom-0 px-3 text-gray-400 hover:text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Mobile User Menu -->
                <div class="pt-4 pb-3 border-t border-gray-700">
                    <div class="px-4 space-y-2">
                        @auth
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('profile.edit') }}" class="block text-sm text-gray-300 hover:text-white">
                                {{ Auth::user()->name }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left text-sm text-gray-300 hover:text-white">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                        @else
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('login') }}" class="block text-sm text-gray-300 hover:text-white">Connexion</a>
                            <a href="{{ route('register') }}" class="block text-sm text-gray-300 hover:text-white">Inscription</a>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Flash Messages -->
        @if (session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="bg-green-500 text-white p-4 rounded-md">
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="bg-red-500 text-white p-4 rounded-md">
                {{ session('error') }}
            </div>
        </div>
        @endif

        <!-- Page Content -->
        <main class="py-8">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 border-t border-gray-700 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                    <div>
                        <p class="text-gray-400 text-sm">
                            © {{ date('Y') }} MyGame - Tous droits réservés
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">
                            Propulsé par l'API <a href="https://rawg.io/" class="text-purple-500 hover:text-purple-400">RAWG</a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>