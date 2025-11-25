<?php

namespace App\Notifications\Channels;

use App\Facades\Fonnte;
use App\Models\User;

class WhatsappChannel
{
    /**
     * Send the given notification.
     *
     * @param  \App\Models\User  $notifiable
     * @param  mixed  $notification  Notification instance that provides toWhatsApp()
     * @return void
     */
    public function send(User $notifiable, $notification)
    {
        try {
            $message = $notification->toWhatsApp($notifiable);
            Fonnte::sendMessage(
                $message->phoneNumber,
                $message->message
            );
        } catch (\Exception $exception) {
            report($exception);
        }
    }
}
