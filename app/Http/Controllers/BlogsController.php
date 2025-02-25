<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogsController extends Controller
{
    public function blogs() {
        $posts = Post::latest()->simplePaginate(9);
        return view('frontend.home', compact([
            'posts'
        ]));
    }

    public function singleBlog(Request $request, string $slug) {
        $post = Post::where('slug', $slug)->first();
        return view('frontend.blog', compact([
            'post'
        ]));
    }

}
