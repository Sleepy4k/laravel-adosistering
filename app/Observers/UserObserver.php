<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\PasswordChanged;
use Illuminate\Support\Facades\Notification;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if ($user->wasChanged('password')) {
            Notification::sendNow($user, new PasswordChanged($user->personal->full_name, $user->email));
        }
    }
}
