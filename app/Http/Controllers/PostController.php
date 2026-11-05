<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return view('pages.posts.index', [
            'posts' => Post::published()->latest('published_at')->paginate(9),
        ]);
    }

    public function show(Post $post)
    {
        abort_unless($post->is_published && $post->published_at?->isPast(), 404);

        return view('pages.posts.show', [
            'post' => $post,
            'related' => Post::published()->whereKeyNot($post->id)->latest('published_at')->take(2)->get(),
        ]);
    }
}
