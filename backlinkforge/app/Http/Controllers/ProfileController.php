<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        return Inertia::render('Profile/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_photo_url' => $user->profile_photo_url,
                'current_team' => $user->currentTeam ? [
                    'id' => $user->currentTeam->id,
                    'name' => $user->currentTeam->name,
                    'personal_team' => $user->currentTeam->personal_team,
                ] : null,
                'teams' => $user->allTeams()->map(function ($team) {
                    return [
                        'id' => $team->id,
                        'name' => $team->name,
                        'personal_team' => $team->personal_team,
                        'role' => $team->users->where('id', auth()->id())->first()->membership->role,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        return back()->with('status', 'Profile updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        $user->delete();

        return redirect('/');
    }
} 