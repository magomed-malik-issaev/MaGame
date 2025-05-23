<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
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

    /**
     * Affiche la liste des utilisateurs
     */
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Promeut un utilisateur au rôle d'administrateur
     */
    public function promoteUser(User $user)
    {
        $user->update(['role' => 'admin']);
        return redirect()->back()->with('success', 'L\'utilisateur a été promu administrateur avec succès.');
    }

    /**
     * Rétrograde un administrateur au rôle d'utilisateur
     */
    public function demoteUser(User $user)
    {
        // Empêcher de rétrograder le dernier administrateur
        $adminCount = User::where('role', 'admin')->count();

        if ($adminCount <= 1 && $user->isAdmin()) {
            return redirect()->back()->with('error', 'Impossible de rétrograder le dernier administrateur.');
        }

        $user->update(['role' => 'user']);
        return redirect()->back()->with('success', 'L\'administrateur a été rétrogradé avec succès.');
    }
}
