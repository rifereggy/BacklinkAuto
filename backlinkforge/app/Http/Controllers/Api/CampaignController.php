<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $campaigns = Campaign::with(['team', 'user'])->paginate();
        return response()->json($campaigns);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'string|in:draft,active,paused,completed',
            'graph_json' => 'array',
            'settings' => 'array',
        ]);

        $campaign = Campaign::create([
            ...$validated,
            'team_id' => $request->user()->currentTeam->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($campaign, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign): JsonResponse
    {
        $this->authorize('view', $campaign);
        return response()->json($campaign->load(['team', 'user', 'accounts', 'contentItems']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);
        
        $validated = $request->validate([
            'name' => 'string|max:255',
            'status' => 'string|in:draft,active,paused,completed',
            'graph_json' => 'array',
            'settings' => 'array',
        ]);

        $campaign->update($validated);
        return response()->json($campaign);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign): JsonResponse
    {
        $this->authorize('delete', $campaign);
        $campaign->delete();
        return response()->json(null, 204);
    }

    /**
     * Start the campaign.
     */
    public function start(Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);
        $campaign->update(['status' => 'active']);
        return response()->json(['message' => 'Campaign started successfully']);
    }

    /**
     * Pause the campaign.
     */
    public function pause(Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);
        $campaign->update(['status' => 'paused']);
        return response()->json(['message' => 'Campaign paused successfully']);
    }

    /**
     * Stop the campaign.
     */
    public function stop(Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);
        $campaign->update(['status' => 'completed']);
        return response()->json(['message' => 'Campaign stopped successfully']);
    }
} 