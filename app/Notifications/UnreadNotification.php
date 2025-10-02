<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UnreadNotification extends Notification
{
    use Queueable;

    protected string $title;
    protected string $message;
    protected string $icon;
    protected string $color;

    public function __construct(string $title, string $message, string $icon = 'heroicon-o-bell', string $color = 'info')
    {
        $this->title = $title;
        $this->message = $message;
        $this->icon = $icon;
        $this->color = $color;
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
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'color' => $this->color,
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'color' => $this->color,
        ];
    }
}
