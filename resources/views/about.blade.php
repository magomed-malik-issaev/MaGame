@extends('layouts.app')

@section('title', 'À propos')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
        <div class="p-6 text-white">
            <h1 class="text-2xl font-bold mb-4">À propos de MyGame</h1>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Notre mission</h2>
                <p class="mb-4">
                    MyGame est une plateforme dédiée aux passionnés de jeux vidéo qui souhaitent
                    découvrir, suivre et partager leurs expériences de jeu.
                </p>
                <p>
                    Notre objectif est de créer une communauté dynamique où les joueurs peuvent
                    gérer leur collection, noter leurs jeux préférés et échanger avec d'autres passionnés.
                </p>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Fonctionnalités</h2>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Découvrez une vaste bibliothèque de jeux</li>
                    <li>Créez et gérez votre collection personnelle</li>
                    <li>Notez les jeux et partagez vos avis</li>
                    <li>Interagissez avec la communauté via les commentaires</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-2">Contact</h2>
                <p>
                    Pour toute question ou suggestion, n'hésitez pas à nous contacter à
                    <a href="mailto:contact@mygame.com" class="text-purple-500 hover:underline">contact@mygame.com</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection