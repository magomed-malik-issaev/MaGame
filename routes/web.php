<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route d'accueil - liste des jeux
Route::get('/', [GameController::class, 'index'])->name('home');

// Routes pour les jeux
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/all', [GameController::class, 'allGames'])->name('games.all');
Route::get('/games/search', [GameController::class, 'search'])->name('games.search');
Route::get('/games/{id}', [GameController::class, 'show'])->name('games.show');

// Liste des utilisateurs
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    // Collection de jeux
    Route::post('/games/{id}/collection', [GameController::class, 'addToCollection'])->name('games.addToCollection');
    Route::delete('/games/{id}/collection', [GameController::class, 'removeFromCollection'])->name('games.removeFromCollection');

    // Commentaires
    Route::post('/games/{game}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Commentaires Ajax
    Route::post('/api/games/{game}/comments', [CommentController::class, 'storeAjax'])->name('api.comments.store');
    Route::put('/api/comments/{comment}', [CommentController::class, 'updateAjax'])->name('api.comments.update');
    Route::delete('/api/comments/{comment}', [CommentController::class, 'destroyAjax'])->name('api.comments.destroy');

    // Notes
    Route::post('/games/{game}/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::delete('/games/{game}/ratings', [RatingController::class, 'destroy'])->name('ratings.destroy');

    // Notes Ajax
    Route::post('/api/games/{game}/ratings', [RatingController::class, 'storeAjax'])->name('api.ratings.store');
    Route::delete('/api/games/{game}/ratings', [RatingController::class, 'destroyAjax'])->name('api.ratings.destroy');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profil public
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');

    // Ma collection de jeux
    Route::get('/my-games', [GameController::class, 'myGames'])->name('games.myGames');

    // Routes admin
    Route::middleware('admin')->group(function () {
        Route::get('/admin/comments', [AdminController::class, 'comments'])->name('admin.comments');
        Route::post('/admin/comments/{comment}', [AdminController::class, 'deleteComment'])->name('admin.comments.delete');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/admin/users/{user}/promote', [AdminController::class, 'promoteUser'])->name('admin.users.promote');
        Route::post('/admin/users/{user}/demote', [AdminController::class, 'demoteUser'])->name('admin.users.demote');

        // Statistiques de l'API
        Route::get('/admin/api-status', [GameController::class, 'apiStatus'])->name('admin.api-status');
    });
});

// Page À propos
Route::get('/about', function () {
    return view('about');
})->name('about');

require __DIR__ . '/auth.php';
