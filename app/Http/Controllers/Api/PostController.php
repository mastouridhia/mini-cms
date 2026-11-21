<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexPostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index(IndexPostRequest $request): AnonymousResourceCollection
    {
        $posts = Post::published()
            ->with('user', 'category')
            ->latest('id')
            ->paginate($request->integer('per_page', 5))
            ->withQueryString();

        return PostResource::collection($posts);
    }

    public function show(Post $post): PostResource
    {
        Gate::authorize('view', $post);

        return new PostResource($post->load('user', 'category'));
    }
}
