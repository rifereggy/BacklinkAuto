<?php

namespace App\Policies;

use App\Models\Proxy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProxyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if (!$user->currentTeam) {
            return false;
        }

        // Only team owners and admins can view proxies
        $membership = $user->currentTeam->users->where('id', $user->id)->first();
        return $membership && in_array($membership->membership->role, ['admin', 'owner']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Proxy $proxy): bool
    {
        if (!$user->currentTeam || $proxy->team_id !== $user->currentTeam->id) {
            return false;
        }

        // Only team owners and admins can view proxies
        $membership = $user->currentTeam->users->where('id', $user->id)->first();
        return $membership && in_array($membership->membership->role, ['admin', 'owner']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if (!$user->currentTeam) {
            return false;
        }

        // Only team owners and admins can create proxies
        $membership = $user->currentTeam->users->where('id', $user->id)->first();
        return $membership && in_array($membership->membership->role, ['admin', 'owner']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Proxy $proxy): bool
    {
        if (!$user->currentTeam || $proxy->team_id !== $user->currentTeam->id) {
            return false;
        }

        // Only team owners and admins can update proxies
        $membership = $user->currentTeam->users->where('id', $user->id)->first();
        return $membership && in_array($membership->membership->role, ['admin', 'owner']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Proxy $proxy): bool
    {
        if (!$user->currentTeam || $proxy->team_id !== $user->currentTeam->id) {
            return false;
        }

        // Only team owners can delete proxies
        $membership = $user->currentTeam->users->where('id', $user->id)->first();
        return $membership && $membership->membership->role === 'owner';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Proxy $proxy): bool
    {
        return $this->delete($user, $proxy);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Proxy $proxy): bool
    {
        return $this->delete($user, $proxy);
    }
} 