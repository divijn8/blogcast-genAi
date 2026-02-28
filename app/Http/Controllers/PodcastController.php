<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.podcasts.create', compact(['categories', 'tags']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'audio_file' => 'nullable|file|mimes:mp3,wav',
            'generated_audio_path' => 'nullable|string',
        ]);

        $podcast = new Podcast();
        $podcast->title = $request->title;

        $podcast->description = $request->description;
        $podcast->category_id = $request->category_id;
        $podcast->author_id = auth()->id();

        if ($request->filled('script_json')) {
            $podcast->script_json = json_decode($request->script_json, true);
        }

        if ($request->hasFile('thumbnail')) {
            $podcast->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->filled('generated_audio_path')) {
            $urlParts = explode('/', $request->generated_audio_path);
            $fileName = end($urlParts);
            $tempPath = "temp/{$fileName}";

            if (Storage::disk('public')->exists($tempPath)) {
                $newPath = "podcasts/audios/{$fileName}";
                Storage::disk('public')->move($tempPath, $newPath);
                $podcast->audio_path = $newPath;
            } else {
                 $podcast->audio_path = "podcasts/audios/{$fileName}";
            }
        }
        elseif ($request->hasFile('audio_file')) {
            $podcast->audio_path = $request->file('audio_file')->store('podcasts/audios', 'public');
        }

        $podcast->save();

        if ($request->has('tags')) {
            $podcast->tags()->sync($request->tags);
        }

        return redirect()->route('admin.podcasts.index')->with('success', 'Podcast created successfully!');
    }

    public function show($slug)
    {
        $podcast = Podcast::where('slug', $slug)
                          ->orWhere('id', $slug)
                          ->firstOrFail();

        $podcast->increment('view_count');

        $categories = Category::where('type', 'podcast')->withCount('podcasts')->get();
        $tags = Tag::whereHas('podcasts')->get();
        return view('frontend.podcast-single', compact('podcast', 'categories', 'tags'));
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
