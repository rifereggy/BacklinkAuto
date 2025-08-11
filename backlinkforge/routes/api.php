<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Team-scoped API routes
    Route::middleware(['ensure.user.is.on.team', 'tenant.scope'])->group(function () {
        // Campaign API routes
        Route::apiResource('campaigns', \App\Http\Controllers\Api\CampaignController::class);
        
        // Template API routes
        Route::apiResource('templates', \App\Http\Controllers\Api\TemplateController::class);
        
        // Proxy API routes (admin only)
        Route::middleware(['can:viewAny,App\Models\Proxy'])->group(function () {
            Route::apiResource('proxies', \App\Http\Controllers\Api\ProxyController::class);
        });
        
        // Account API routes
        Route::apiResource('accounts', \App\Http\Controllers\Api\AccountController::class);
        
        // Job API routes
        Route::apiResource('jobs', \App\Http\Controllers\Api\JobController::class);
        
        // Job execution routes
        Route::post('/jobs/{job}/execute', [\App\Http\Controllers\Api\JobController::class, 'execute']);
        Route::post('/jobs/{job}/cancel', [\App\Http\Controllers\Api\JobController::class, 'cancel']);
        
        // Campaign execution routes
        Route::post('/campaigns/{campaign}/start', [\App\Http\Controllers\Api\CampaignController::class, 'start']);
        Route::post('/campaigns/{campaign}/pause', [\App\Http\Controllers\Api\CampaignController::class, 'pause']);
        Route::post('/campaigns/{campaign}/stop', [\App\Http\Controllers\Api\CampaignController::class, 'stop']);
    });
});
