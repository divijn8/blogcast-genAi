<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class BlogsController extends Controller
{
    public function blogs() {
        $posts = Post::latest()->simplePaginate(9);
        return view('frontend.home', compact([
            'posts'
        ]));
    }

    public function show(Request $request, string $slug) {
        $post = Post::where('slug', $slug)->firstOrFail();
        $this->trackViewCount($post);
        return view('frontend.blog', compact([
            'post'
        ]));

    }

    private function trackViewCount(Post $post) {
        $cookieName = 'blog_viewed_'. $post->id;

        if(!Cookie::has($cookieName)) {
            $post->increment('view_count');

            Cookie::queue($cookieName, true, 60*24);
        }
    }


}
