<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Campaign;
use App\Models\Account;
use App\Models\Proxy;
use App\Models\Template;
use App\Models\ContentItem;
use App\Models\JobLog;

class TenantScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if ($user && $user->currentTeam) {
            $teamId = $user->currentTeam->id;
            
            // Apply team scope to all tenant-aware models
            Campaign::addGlobalScope('team', function ($query) use ($teamId) {
                $query->where('team_id', $teamId);
            });
            
            Account::addGlobalScope('team', function ($query) use ($teamId) {
                $query->whereHas('campaign', function ($q) use ($teamId) {
                    $q->where('team_id', $teamId);
                });
            });
            
            Proxy::addGlobalScope('team', function ($query) use ($teamId) {
                $query->where('team_id', $teamId);
            });
            
            Template::addGlobalScope('team', function ($query) use ($teamId) {
                $query->where(function ($q) use ($teamId) {
                    $q->where('team_id', $teamId)
                      ->orWhere('is_public', true);
                });
            });
            
            ContentItem::addGlobalScope('team', function ($query) use ($teamId) {
                $query->whereHas('campaign', function ($q) use ($teamId) {
                    $q->where('team_id', $teamId);
                });
            });
            
            JobLog::addGlobalScope('team', function ($query) use ($teamId) {
                $query->whereHas('campaign', function ($q) use ($teamId) {
                    $q->where('team_id', $teamId);
                });
            });
        }
        
        return $next($request);
    }
} 