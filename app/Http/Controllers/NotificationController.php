<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends BaseController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = auth()->user()?->notifications();

        if ($request->has('type')) {
            $query->where('data->type', $request->type);
        }

        if ($request->has('read')) {
            if ($request->read) {
                $query->whereNotNull('read_at');
            } else {
                $query->whereNull('read_at');
            }
        }

        $notifications = $query
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return NotificationResource::collection($notifications);
    }

    public function markAsRead(DatabaseNotification $notification): JsonResponse
    {
        if ($notification->notifiable_id !== auth()->id()) {
            return $this->sendError('Not found');
        }

        $notification->markAsRead();

        return $this->sendResponse(true);
    }
}
