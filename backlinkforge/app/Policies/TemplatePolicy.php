<?php

namespace App\Policies;

use App\Models\Template;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Public templates are viewable by all
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Template $template): bool
    {
        // Public templates are viewable by all
        if ($template->is_public) {
            return true;
        }

        // Team-specific templates require team membership
        return $user->currentTeam && $template->team_id === $user->currentTeam->id;
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
    public function update(User $user, Template $template): bool
    {
        if (!$user->currentTeam) {
            return false;
        }

        // Can only update team-specific templates
        if ($template->team_id !== $user->currentTeam->id) {
            return false;
        }

        // Only team owners and admins can update templates
        $membership = $user->currentTeam->users->where('id', $user->id)->first();
        return $membership && in_array($membership->membership->role, ['admin', 'owner']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Template $template): bool
    {
        if (!$user->currentTeam) {
            return false;
        }

        // Can only delete team-specific templates
        if ($template->team_id !== $user->currentTeam->id) {
            return false;
        }

        // Only team owners can delete templates
        $membership = $user->currentTeam->users->where('id', $user->id)->first();
        return $membership && $membership->membership->role === 'owner';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Template $template): bool
    {
        return $this->delete($user, $template);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Template $template): bool
    {
        return $this->delete($user, $template);
    }
} 