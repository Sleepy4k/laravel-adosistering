<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChanged extends Notification
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
            ->subject('Kata Sandi Akun Anda Telah Diubah')
            ->greeting("Halo {$this->fullname},")
            ->line("Kata sandi untuk akun Anda ({$this->email}) telah berhasil diubah.")
            ->line('Jika Anda tidak melakukan perubahan ini atau jika Anda yakin ada orang yang tidak berwenang mengakses akun Anda, harap segera hubungi dukungan.')
            ->line('Jika Anda memiliki pertanyaan atau kekhawatiran, jangan ragu untuk menghubungi tim dukungan kami.');
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
