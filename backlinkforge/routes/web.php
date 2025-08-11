<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Team routes
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
    Route::post('/teams/{team}/switch', [TeamController::class, 'switch'])->name('teams.switch');

    // Team-scoped routes (require team membership)
    Route::middleware(['ensure.user.is.on.team', 'tenant.scope'])->group(function () {
        // Campaign routes
        Route::resource('campaigns', \App\Http\Controllers\CampaignController::class);
        
        // Template routes
        Route::resource('templates', \App\Http\Controllers\TemplateController::class);
        
        // Proxy routes (admin only)
        Route::middleware(['can:viewAny,App\Models\Proxy'])->group(function () {
            Route::resource('proxies', \App\Http\Controllers\ProxyController::class);
        });
        
        // Account routes
        Route::resource('accounts', \App\Http\Controllers\AccountController::class);
        
        // Job routes
        Route::resource('jobs', \App\Http\Controllers\JobController::class);
    });
});
