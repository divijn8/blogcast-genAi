<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Models\Category;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    public function index()
    {
        $podcasts = Podcast::with('category')->latest()->paginate(10);
        $totalListens = Podcast::sum('view_count');
        $podcastCategories = Category::where('type', 'podcast')->withCount('podcasts')->get();

        if (request()->is('admin/*')) {
            return view('admin.podcasts.index', compact('podcasts'));
        }
        return view('frontend.podcast', compact('podcasts', 'totalListens', 'podcastCategories'));
    }

    public function create()
    {
        $categories = Category::where('type', 'podcast')->get();
        return view('admin.podcasts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'thumbnail' => 'image|mimes:jpeg,png,jpg',
        ]);

        // File handling logic similar to your PostsController...
        Podcast::create($data + ['author_id' => auth()->id()]);

        return redirect()->route('podcasts.index')->with('success', 'Podcast created successfully!');
    }
}
