<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->currentTeam !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Campaign $campaign): bool
    {
        return $user->currentTeam && $campaign->team_id === $user->currentTeam->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->currentTeam !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Campaign $campaign): bool
    {
        if (!$user->currentTeam || $campaign->team_id !== $user->currentTeam->id) {
            return false;
        }

        // Only team owners and admins can update campaigns
        $membership = $user->currentTeam->users->where('id', $user->id)->first();
        return $membership && in_array($membership->membership->role, ['admin', 'owner']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Campaign $campaign): bool
    {
        if (!$user->currentTeam || $campaign->team_id !== $user->currentTeam->id) {
            return false;
        }

        // Only team owners can delete campaigns
        $membership = $user->currentTeam->users->where('id', $user->id)->first();
        return $membership && $membership->membership->role === 'owner';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Campaign $campaign): bool
    {
        return $this->delete($user, $campaign);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Campaign $campaign): bool
    {
        return $this->delete($user, $campaign);
    }
} 