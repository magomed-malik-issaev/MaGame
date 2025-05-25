<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's public profile.
     */
    public function show($id): View
    {
        $user = \App\Models\User::findOrFail($id);
        $games = $user->games()->get();
        $favoriteGames = $games->where('pivot.status', 'favori');
        $ratings = $user->ratings()->with('game')->get();

        return view('profile.show', [
            'user' => $user,
            'games' => $games,
            'favoriteGames' => $favoriteGames,
            'ratings' => $ratings,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Récupérer les données validées
        $validated = $request->validated();

        // Gérer l'upload d'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar s'il existe
            if ($request->user()->avatar) {
                Storage::disk('public')->delete($request->user()->avatar);
            }

            // Stocker le nouvel avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        // Formater la date de naissance
        if (isset($validated['birth_date']) && $validated['birth_date']) {
            $validated['birth_date'] = \Carbon\Carbon::parse($validated['birth_date'])->format('Y-m-d');
        }

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Supprimer l'avatar s'il existe
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
