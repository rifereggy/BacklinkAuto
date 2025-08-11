<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::with(['campaign', 'proxy'])->paginate();
        
        return Inertia::render('Accounts/Index', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Accounts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'provider' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password_encrypted' => 'required|string',
            'proxy_id' => 'exists:proxies,id',
            'status' => 'string|in:active,inactive,testing,failed',
        ]);

        $account = Account::create($validated);

        return redirect()->route('accounts.show', $account);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        return Inertia::render('Accounts/Show', [
            'account' => $account->load(['campaign', 'proxy']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        return Inertia::render('Accounts/Edit', [
            'account' => $account,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'provider' => 'string|max:255',
            'username' => 'string|max:255',
            'email' => 'email|max:255',
            'password_encrypted' => 'string',
            'proxy_id' => 'exists:proxies,id',
            'status' => 'string|in:active,inactive,testing,failed',
        ]);

        $account->update($validated);

        return redirect()->route('accounts.show', $account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()->route('accounts.index');
    }
} 