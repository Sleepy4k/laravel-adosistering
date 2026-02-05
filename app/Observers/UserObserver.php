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
            // Use name from user model, since details->full_name may not exist
            $fullName = $user->name ?? 'User';
            Notification::sendNow($user, new PasswordChanged($fullName, $user->email));
        }
    }
}
