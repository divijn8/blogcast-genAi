<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blogs() {
        $posts = Post::latest()->simplePaginate(9);
        return view('frontend.home', compact([
            'posts'
        ]));
    }
}
