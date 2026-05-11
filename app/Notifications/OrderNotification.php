<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->details['title'] ?? 'Notifikasi Baru',
            'message' => $this->details['message'] ?? '',
            'order_uuid' => $this->details['order_uuid'] ?? null,
            'icon' => $this->details['icon'] ?? 'bi-info-circle',
            'type' => $this->details['type'] ?? 'primary',
            'url' => $this->details['url'] ?? '#',
        ];
    }
}
