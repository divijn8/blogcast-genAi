<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

class BlogsController extends Controller
{
    public function blogs() {
        $search = request()->query('search');
        $showDrafts = request()->query('draft');  // Check for 'draft' query param
        $categories = Category::all();
        $tags = Tag::all();

        // Only fetch drafts for authenticated users
        $query = Post::with('author')->latest() ;

        // Apply search if there is one
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        // Fetch drafts if the user is logged in and the draft flag is set
        if ($showDrafts && Auth::check()) {
            $query->where('user_id', Auth::id())->whereNull('published_at');
        } else {
            $query->whereNotNull('published_at');  // Only fetch published posts
        }

        // Paginate the results
        $posts = $query->published()
            ->orderBy('published_at', 'desc')->simplePaginate(9);

        return view('frontend.home', compact([
            'posts',
            'categories',
            'tags'
        ]));
    }

    public function publishBlog(Post $blog, Request $request) {
        $blog->published_at = $request->published_at;
        $blog->save();
        session()->flash('success', 'Blog Set to Publish successfully');
        return redirect(route('admin.posts.draft'));
    }


    public function draft()
    {
        $authUser = auth()->user();
        $blogs = Post::with('category')
                    ->where('author_id', $authUser->id)
                    ->where('published_at', null)
                    ->latest('updated_at')
                    ->paginate(2);
        return view("admin.posts.draft", compact('blogs'));
    }

    public function show(Request $request, string $slug) {
        $post = Post::where('slug', $slug)->firstOrFail();
        $categories = Category::all();
        $tags = Tag::all();

        // Track the view count if not already tracked
        $this->trackViewCount($post);

        return view('frontend.blog', compact([
            'post',
            'categories',
            'tags'
        ]));
    }

    private function trackViewCount(Post $post) {
        $cookieName = 'blog_viewed_'. $post->id;

        if(!Cookie::has($cookieName)) {
            $post->increment('view_count');
            Cookie::queue($cookieName, true, 60*24);
        }
    }

    public function showByCategory(Request $request, $slug) {
        $category = Category::where('slug', $slug)->firstOrFail();
        $categories = Category::all();
        $tags = Tag::all();

        // Handle drafts in categories, only show if user is authorized
        $query = Post::where('category_id', $category->id)->with('author')->latest();

        if (request()->query('draft') && Auth::check()) {
            $query->where('user_id', Auth::id())->whereNull('published_at');
        } else {
            $query->whereNotNull('published_at');
        }

        $posts = $query->simplePaginate(9);

        return view('frontend.categories', compact([
            'category',
            'posts',
            'categories',
            'tags'
        ]));
    }

    public function showByTag(Request $request, $tagName) {
        $tag = Tag::where('name', $tagName)->firstOrFail();
        $tags = Tag::all();
        $categories = Category::all();

        // Handle drafts in tags, only show if user is authorized
        $query = Post::whereHas('tags', function ($query) use ($tag) {
            $query->where('tags.name', $tag->name);
        })->with('author')->latest();

        if (request()->query('draft') && Auth::check()) {
            $query->where('user_id', Auth::id())->whereNull('published_at');
        } else {
            $query->whereNotNull('published_at');
        }

        $posts = $query->simplePaginate(9);

        return view('frontend.tags', compact([
            'tags',
            'posts',
            'categories',
            'tag'
        ]));
    }
}
