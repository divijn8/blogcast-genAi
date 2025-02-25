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
}
