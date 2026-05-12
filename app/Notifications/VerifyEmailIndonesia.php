<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailIndonesia extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Aktivasi Akun Marketplace Polman')
            ->greeting('Halo, Rekan Polman!')
            ->line('Terima kasih telah mendaftar. Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda dan mulai menggunakan layanan kami.')
            ->action('Verifikasi Email Saya', $verificationUrl)
            ->line('Jika Anda tidak merasa mendaftar di platform kami, abaikan saja email ini.')
            ->salutation('Salam hangat, Tim IT Marketplace Polman');
    }
}