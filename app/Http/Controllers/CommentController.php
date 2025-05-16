<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class CommentController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Appliquer le middleware auth à toutes les méthodes
        $this->middleware('auth');
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
        // Vérifier que l'utilisateur est soit le propriétaire du commentaire, soit un administrateur
        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
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
        // Vérifier que l'utilisateur est soit le propriétaire du commentaire, soit un administrateur
        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce commentaire');
        }

        $comment->delete();

        return back()->with('success', 'Commentaire supprimé avec succès');
    }

    /**
     * Store a newly created comment in storage via Ajax.
     */
    public function storeAjax(Request $request, Game $game)
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

        // Charger la relation utilisateur pour l'inclure dans la réponse
        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Commentaire ajouté avec succès',
            'comment' => $comment,
            'user' => [
                'name' => $comment->user->name,
                'isAdmin' => $comment->user->isAdmin()
            ],
            'created_at_formatted' => $comment->created_at->diffForHumans()
        ]);
    }

    /**
     * Update the specified comment in storage via Ajax.
     */
    public function updateAjax(Request $request, Comment $comment)
    {
        // Vérifier que l'utilisateur est soit le propriétaire du commentaire, soit un administrateur
        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier ce commentaire'
            ], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Commentaire mis à jour avec succès',
            'comment' => $comment
        ]);
    }

    /**
     * Remove the specified comment from storage via Ajax.
     */
    public function destroyAjax(Comment $comment)
    {
        // Vérifier que l'utilisateur est soit le propriétaire du commentaire, soit un administrateur
        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à supprimer ce commentaire'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commentaire supprimé avec succès'
        ]);
    }
}
