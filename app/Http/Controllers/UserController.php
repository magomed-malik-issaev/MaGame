<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Affiche la liste des utilisateurs
     */
    public function index()
    {
        $users = User::withCount(['games', 'ratings', 'comments'])
            ->orderBy('games_count', 'desc')
            ->paginate(20);

        return view('users.index', compact('users'));
    }
}
