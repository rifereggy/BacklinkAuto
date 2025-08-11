<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Campaign;
use App\Models\Template;
use App\Models\Proxy;
use App\Policies\CampaignPolicy;
use App\Policies\TemplatePolicy;
use App\Policies\ProxyPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(Campaign::class, CampaignPolicy::class);
        Gate::policy(Template::class, TemplatePolicy::class);
        Gate::policy(Proxy::class, ProxyPolicy::class);
    }
}
