<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Notifications\MarketingNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PostNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_post(): void
    {
        dump(DB::connection()->getDatabaseName());
        $author = User::factory()->create();

        $postData = [
            'title' => 'Новая статья о Laravel',
            'content' => 'Подробное руководство по уведомлениям...',
        ];

        $response = $this
            ->actingAs($author)
            ->postJson('/api/posts', $postData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('posts', [
            'title' => $postData['title'],
            'content' => $postData['content'],
            'user_id' => $author->id,
        ]);
    }
    /**
     * Тест: при создании поста уведомления отправляются всем, кроме автора.
     */
    public function test_sent_notifications_when_post_created(): void
    {
        Notification::fake();

        $author = User::factory()->create();
        $subscriber1 = User::factory()->create();
        $subscriber2 = User::factory()->create();

        $postData = [
            'title' => 'Новая статья о Laravel',
            'content' => 'Подробное руководство по уведомлениям...',
        ];

        $this
            ->actingAs($author)
            ->postJson('/api/posts', $postData);

        Notification::assertSentTo(
            [$subscriber1, $subscriber2],
            MarketingNotification::class,
            function (MarketingNotification $notification) use ($postData) {
                return $notification->title === 'Новая публикация: ' . $postData['title'];
            }
        );

        Notification::assertNotSentTo($author, MarketingNotification::class);
    }
}
