<?php

namespace App\Policies\Dashboard;

use App\Models\User;

class IrrigationSettingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('irrigation_setting.view');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->can("irrigation_setting.update");
    }
}
