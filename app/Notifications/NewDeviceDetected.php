<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDeviceDetected extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $fullname,
        protected string $email
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
            ->subject("Notifikasi Perangkat Baru Terdeteksi")
            ->greeting("Halo {$this->fullname},")
            ->line("Kami mendeteksi perangkat baru yang digunakan untuk mengakses akun Anda ({$this->email}).")
            ->line("Jika ini bukan Anda, harap segera ubah kata sandi Anda.")
            ->action("Lihat Aktivitas", url(route('profile.security.index')))
            ->line("Jika ini adalah aktivitas yang sah, tidak diperlukan tindakan lebih lanjut.");
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
