<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Models\User;
use App\Notifications\MarketingNotification;
use App\Notifications\SystemNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewPostNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostCreated $event)
    {
        $post = $event->post;

        $users = User::where('id', '!=', $post->user_id)
            ->get();

        $title = 'Новая публикация: ' . $post->title;
        $message = 'Пользователь ' . $post->user->name . ' опубликовал новый пост.';

        foreach ($users as $user) {
            $user->notify(new MarketingNotification($title, $message));
        }
    }
}
