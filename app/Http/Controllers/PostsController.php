<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        // Eager loading
        // SELECT * FROM posts
        // SELECT * FROM categories WHERE id IN (1,2,3)
        $posts = Post::with('category')->paginate(15);

        return view('front.posts.index', [
            'posts' => $posts,
        ]);
    }

    public function show($id)
    {
        $post = Post::published()->findOrFail($id);
        return view('front.posts.show', [
            'post' => $post,
        ]);
    }
}
