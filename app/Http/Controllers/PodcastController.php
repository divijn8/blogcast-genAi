<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    public function index()
    {
        $podcasts = Podcast::with(['category', 'tags'])->latest()->paginate(10);
        $totalListens = Podcast::sum('view_count');
        $categories = Category::where('type', 'podcast')->withCount('podcasts')->get();

        if (request()->is('admin/*')) {
            return view('admin.podcasts.index', compact('podcasts'));
        }

        $tags = Tag::whereHas('podcasts')->get();
        return view('frontend.podcast', compact('podcasts', 'totalListens', 'categories', 'tags'));
    }

    public function create()
    {
        $categories = Category::where('type', 'podcast')->get();
        return view('admin.podcasts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            // 'script_json' => 'required', // Ensure it's not empty
        ]);
        $podcast = new Podcast();
        $podcast->title = $request->title;
        $podcast->description = $request->description;
        $podcast->category_id = $request->category_id;
        $podcast->author_id = auth()->id();

        $podcast->script_json = json_decode($request->script_json, true);

        if ($request->hasFile('thumbnail')) {
            $podcast->thumbnail = $request->file('thumbnail')->store('podcasts/thumbnails', 'public');
        }
        if ($request->hasFile('audio_file')) {
            $podcast->audio_path = $request->file('audio_file')->store('podcasts/audios', 'public');
        }
        $podcast->save();

        return redirect()->route('admin.podcasts.index')->with('success', 'Podcast created!');
    }

    public function drafts()
    {
        $authUser = auth()->user();
        $podcasts = Podcast::with('category')
                    ->where('author_id', $authUser->id)
                    ->where('published_at', null)
                    ->latest('updated_at')
                    ->paginate(10);
        return view("admin.podcasts.drafts", compact('podcasts'));
    }
}
