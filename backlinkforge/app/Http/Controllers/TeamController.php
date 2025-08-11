<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\Team;
use App\Models\User;

class TeamController extends Controller
{
    /**
     * Display the user's teams.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        return Inertia::render('Teams/Index', [
            'teams' => $user->allTeams()->map(function ($team) {
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                    'personal_team' => $team->personal_team,
                    'role' => $team->users->where('id', auth()->id())->first()->membership->role,
                    'member_count' => $team->users->count(),
                ];
            }),
            'current_team' => $user->currentTeam ? [
                'id' => $user->currentTeam->id,
                'name' => $user->currentTeam->name,
                'personal_team' => $user->currentTeam->personal_team,
            ] : null,
        ]);
    }

    /**
     * Display the specified team.
     */
    public function show(Request $request, Team $team)
    {
        $user = $request->user();
        
        // Ensure user is a member of the team
        if (!$team->hasUser($user)) {
            abort(403);
        }

        return Inertia::render('Teams/Show', [
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
                'personal_team' => $team->personal_team,
                'role' => $team->users->where('id', $user->id)->first()->membership->role,
                'members' => $team->users->map(function ($member) {
                    return [
                        'id' => $member->id,
                        'name' => $member->name,
                        'email' => $member->email,
                        'role' => $member->membership->role,
                        'profile_photo_url' => $member->profile_photo_url,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Show the form for creating a new team.
     */
    public function create()
    {
        return Inertia::render('Teams/Create');
    }

    /**
     * Store a newly created team.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();
        
        $team = $user->ownedTeams()->create([
            'name' => $validated['name'],
            'personal_team' => false,
        ]);

        $user->teams()->attach($team, ['role' => 'owner']);

        return redirect()->route('teams.show', $team);
    }

    /**
     * Update the specified team.
     */
    public function update(Request $request, Team $team)
    {
        $user = $request->user();
        
        // Ensure user is team owner or admin
        $membership = $team->users->where('id', $user->id)->first();
        if (!$membership || !in_array($membership->membership->role, ['admin', 'owner'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $team->update($validated);

        return back()->with('status', 'Team updated successfully.');
    }

    /**
     * Remove the specified team.
     */
    public function destroy(Request $request, Team $team)
    {
        $user = $request->user();
        
        // Ensure user is team owner
        $membership = $team->users->where('id', $user->id)->first();
        if (!$membership || $membership->membership->role !== 'owner') {
            abort(403);
        }

        $team->delete();

        return redirect()->route('teams.index');
    }

    /**
     * Switch to the specified team.
     */
    public function switch(Request $request, Team $team)
    {
        $user = $request->user();
        
        // Ensure user is a member of the team
        if (!$team->hasUser($user)) {
            abort(403);
        }

        $user->switchTeam($team);

        return redirect()->route('dashboard');
    }
} 