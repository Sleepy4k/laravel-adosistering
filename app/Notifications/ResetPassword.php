<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $fullname,
        protected string $url
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Permintaan Reset Password")
            ->greeting("Halo {$this->fullname},")
            ->line("Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.")
            ->action("Reset Password", $this->url)
            ->line("Link ini akan kadaluarsa dalam 60 menit.")
            ->line("Jika Anda tidak meminta reset password, tidak ada tindakan lebih lanjut yang diperlukan.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
