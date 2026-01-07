<?php

namespace App\Policies\Dashboard;

use App\Models\User;

class ProfilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('profile.view');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string $profileType): bool
    {
        return $user->can("profile.update.{$profileType}");
    }
}
