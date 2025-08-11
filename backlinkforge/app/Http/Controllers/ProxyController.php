<?php

namespace App\Http\Controllers;

use App\Models\Proxy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProxyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Proxy::class);
        
        $proxies = Proxy::with(['team'])->paginate();
        
        return Inertia::render('Proxies/Index', [
            'proxies' => $proxies,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Proxy::class);
        
        return Inertia::render('Proxies/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

        return redirect()->route('proxies.show', $proxy);
    }

    /**
     * Display the specified resource.
     */
    public function show(Proxy $proxy)
    {
        $this->authorize('view', $proxy);
        
        return Inertia::render('Proxies/Show', [
            'proxy' => $proxy->load(['team', 'accounts']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proxy $proxy)
    {
        $this->authorize('update', $proxy);
        
        return Inertia::render('Proxies/Edit', [
            'proxy' => $proxy,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proxy $proxy)
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

        return redirect()->route('proxies.show', $proxy);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proxy $proxy)
    {
        $this->authorize('delete', $proxy);
        
        $proxy->delete();

        return redirect()->route('proxies.index');
    }
} 