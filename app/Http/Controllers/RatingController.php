<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Store a newly created rating in storage.
     */
    public function store(Request $request, Game $game)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();

        // Vérifier si l'utilisateur a déjà noté ce jeu
        $existingRating = $user->ratings()->where('game_id', $game->id)->first();

        if ($existingRating) {
            // Mise à jour de la note existante
            $existingRating->update([
                'rating' => $request->input('rating'),
            ]);

            $message = 'Note mise à jour avec succès';
        } else {
            // Création d'une nouvelle note
            $rating = new Rating([
                'rating' => $request->input('rating'),
            ]);

            $rating->user()->associate($user);
            $rating->game()->associate($game);
            $rating->save();

            $message = 'Merci pour votre note !';
        }

        return back()->with('success', $message);
    }

    /**
     * Remove the specified rating from storage.
     */
    public function destroy(Game $game)
    {
        $user = Auth::user();

        // Supprimer la note de l'utilisateur pour ce jeu
        $user->ratings()->where('game_id', $game->id)->delete();

        return back()->with('success', 'Note supprimée avec succès');
    }

    /**
     * Store a newly created rating in storage via Ajax.
     */
    public function storeAjax(Request $request, Game $game)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();
        $isNewRating = false;

        // Vérifier si l'utilisateur a déjà noté ce jeu
        $existingRating = $user->ratings()->where('game_id', $game->id)->first();

        if ($existingRating) {
            // Mise à jour de la note existante
            $existingRating->update([
                'rating' => $request->input('rating'),
            ]);
            $rating = $existingRating;
            $message = 'Note mise à jour avec succès';
        } else {
            // Création d'une nouvelle note
            $rating = new Rating([
                'rating' => $request->input('rating'),
            ]);

            $rating->user()->associate($user);
            $rating->game()->associate($game);
            $rating->save();
            $isNewRating = true;
            $message = 'Merci pour votre note !';
        }

        // Calculer la nouvelle note moyenne du jeu
        $averageRating = $game->ratings()->avg('rating');
        $game->rating = round($averageRating, 1);
        $game->save();

        return response()->json([
            'success' => true,
            'message' => $message,
            'rating' => $rating->rating,
            'averageRating' => $game->rating,
            'isNewRating' => $isNewRating
        ]);
    }

    /**
     * Remove the specified rating from storage via Ajax.
     */
    public function destroyAjax(Game $game)
    {
        $user = Auth::user();

        // Supprimer la note de l'utilisateur pour ce jeu
        $user->ratings()->where('game_id', $game->id)->delete();

        // Recalculer la note moyenne du jeu
        $averageRating = $game->ratings()->avg('rating') ?: 0;
        $game->rating = round($averageRating, 1);
        $game->save();

        return response()->json([
            'success' => true,
            'message' => 'Note supprimée avec succès',
            'averageRating' => $game->rating
        ]);
    }
}
