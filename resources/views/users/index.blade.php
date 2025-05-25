@extends('layouts.app')

@section('title', 'Tous les utilisateurs')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-white mb-8">Découvrez la communauté</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($users as $user)
        <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform duration-300 hover:shadow-xl">
            <div class="p-6">
                <div class="flex flex-col items-center mb-4">
                    <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-700 mb-4">
                        @if ($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        @endif
                    </div>

                    <h2 class="text-xl font-semibold text-white text-center">
                        <a href="{{ route('profile.show', $user->id) }}" class="hover:text-purple-400">
                            {{ $user->name }}
                        </a>
                    </h2>

                    @if($user->isAdmin())
                    <span class="mt-1 px-2 py-1 bg-red-600 text-white text-xs font-medium rounded-full">Admin</span>
                    @endif
                </div>

                <div class="space-y-2 text-center">
                    <div class="flex justify-center space-x-6 text-sm text-gray-400">
                        <div>
                            <span class="block text-purple-400 font-bold">{{ $user->games_count }}</span>
                            <span>Jeux</span>
                        </div>
                        <div>
                            <span class="block text-purple-400 font-bold">{{ $user->ratings_count }}</span>
                            <span>Avis</span>
                        </div>
                        <div>
                            <span class="block text-purple-400 font-bold">{{ $user->comments_count }}</span>
                            <span>Comments</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('profile.show', $user->id) }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded-md transition-colors">
                            Voir le profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $users->links() }}
    </div>
</div>
@endsection