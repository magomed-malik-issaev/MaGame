<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Appliquer le middleware auth à toutes les méthodes sauf index et show
        // $this->middleware('auth');
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Game $game)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment([
            'content' => $request->input('content'),
        ]);

        $comment->user()->associate(Auth::user());
        $comment->game()->associate($game);
        $comment->save();

        return back()->with('success', 'Commentaire ajouté avec succès');
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Vérifier que l'utilisateur est bien le propriétaire du commentaire
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce commentaire');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->input('content'),
        ]);

        return back()->with('success', 'Commentaire mis à jour avec succès');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        // Vérifier que l'utilisateur est bien le propriétaire du commentaire
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce commentaire');
        }

        $comment->delete();

        return back()->with('success', 'Commentaire supprimé avec succès');
    }
}
