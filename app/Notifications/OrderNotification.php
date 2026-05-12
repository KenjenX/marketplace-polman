<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification
{
    use Queueable;

    protected $details;

    /**
     * Create a new notification instance.
     * $details harus berisi array dengan key 'for_admin' (true/false)
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     * Data ini yang akan disimpan di kolom 'data' (JSON) tabel notifications
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'      => $this->details['title'] ?? 'Notifikasi Baru',
            'message'    => $this->details['message'] ?? '',
            'order_uuid' => $this->details['order_uuid'] ?? null,
            'icon'       => $this->details['icon'] ?? 'bi-info-circle',
            'type'       => $this->details['type'] ?? 'primary',
            'url'        => $this->details['url'] ?? '#',
            
            // LOGIKA PEMISAH: true untuk Admin, false untuk User
            'for_admin'  => $this->details['for_admin'] ?? false, 
        ];
    }
}