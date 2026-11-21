<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostController extends Controller
{
    public function index(): LengthAwarePaginator
    {
        return Post::published()->with('user')->latest('id')->paginate(5);
    }
}
