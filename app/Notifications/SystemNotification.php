<?php

namespace App\Notifications;

use App\Enums\TypeNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
    ) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->title)
            ->line($this->message)
            ->action('Перейти в личный кабинет', url('/'));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => TypeNotification::SYSTEM->value,
            'title' => $this->title,
            'message' => $this->message,
        ];
    }
}
