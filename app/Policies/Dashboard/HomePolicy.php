<?php

namespace App\Policies\Dashboard;

use App\Models\User;

class HomePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('dashboard.view');
    }
}
