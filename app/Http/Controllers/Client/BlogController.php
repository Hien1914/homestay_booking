<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'published')->latest()->paginate(15);
        return view('clients.blog.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = Post::where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        $post->increment('views');

        $relatedPosts = Post::where('status', 'published')
            ->whereKeyNot($post->id)
            ->latest()
            ->limit(3)
            ->get();

        return view('clients.blog.show', compact('post', 'relatedPosts'));
    }
}

