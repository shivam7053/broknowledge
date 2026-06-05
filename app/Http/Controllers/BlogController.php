<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Post::where('is_published', true)
            ->with('category')
            ->latest()
            ->get();

        return view('blog.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        abort_if(!$post->is_published, 404);
        
        return view('blog.show', compact('post'));
    }
}