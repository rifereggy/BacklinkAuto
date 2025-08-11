<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proxy;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProxyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Proxy::class);
        $proxies = Proxy::with(['team'])->paginate();
        return response()->json($proxies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Proxy::class);
        
        $validated = $request->validate([
            'ip' => 'required|string|max:45',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'string|max:255',
            'password' => 'string|max:255',
            'type' => 'required|string|in:http,https,socks4,socks5',
            'country' => 'string|max:2',
            'status' => 'string|in:active,inactive,testing',
        ]);

        $proxy = Proxy::create([
            ...$validated,
            'team_id' => $request->user()->currentTeam->id,
        ]);

        return response()->json($proxy, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Proxy $proxy): JsonResponse
    {
        $this->authorize('view', $proxy);
        return response()->json($proxy->load(['team', 'accounts']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proxy $proxy): JsonResponse
    {
        $this->authorize('update', $proxy);
        
        $validated = $request->validate([
            'ip' => 'string|max:45',
            'port' => 'integer|min:1|max:65535',
            'username' => 'string|max:255',
            'password' => 'string|max:255',
            'type' => 'string|in:http,https,socks4,socks5',
            'country' => 'string|max:2',
            'status' => 'string|in:active,inactive,testing',
        ]);

        $proxy->update($validated);
        return response()->json($proxy);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proxy $proxy): JsonResponse
    {
        $this->authorize('delete', $proxy);
        $proxy->delete();
        return response()->json(null, 204);
    }
} 