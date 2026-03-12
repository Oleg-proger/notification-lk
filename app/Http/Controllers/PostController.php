<?php

namespace App\Http\Controllers;

use App\Events\PostCreated;
use App\Http\Requests\PostRequest;
use Illuminate\Http\JsonResponse;

class PostController extends BaseController
{
    public function store(PostRequest $request): JsonResponse
    {
        $data = $request->validated();

        $post = $request->user()->posts()->create($data);

        event(new PostCreated($post));

        return $this->sendResponse(true, 201);
    }
}
