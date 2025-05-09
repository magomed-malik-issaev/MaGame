<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Supprime un commentaire
     */
    public function deleteComment(Comment $comment)
    {
        $comment->delete();
        return redirect()->back()->with('success', 'Commentaire supprimé avec succès.');
    }

    /**
     * Affiche la liste des commentaires pour modération
     */
    public function comments()
    {
        $comments = Comment::with(['user', 'game'])->latest()->paginate(20);
        return view('admin.comments', compact('comments'));
    }
}
