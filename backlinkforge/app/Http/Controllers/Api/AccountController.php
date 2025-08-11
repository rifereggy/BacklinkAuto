<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $accounts = Account::with(['campaign', 'proxy'])->paginate();
        return response()->json($accounts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
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
        return response()->json($account, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account): JsonResponse
    {
        return response()->json($account->load(['campaign', 'proxy']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account): JsonResponse
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
        return response()->json($account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account): JsonResponse
    {
        $account->delete();
        return response()->json(null, 204);
    }
} 