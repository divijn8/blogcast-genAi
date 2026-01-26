<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Jobs\DispatchPostNotificationJob;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if($user->isAdmin()){
            $posts=Post::with('category')->with('tags')->orderBy("id", "desc")->paginate(20);
            }else {
            $posts=$user->posts()->with('category')->with('tags')->orderBy("id", "desc")->paginate(20);
        }
        return view('admin.posts.index',compact([
            'posts'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories=Category::all();
        $tags=Tag::all();
        return view('admin.posts.create',compact([
            'categories',
            'tags'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest $request) {
    DB::beginTransaction();

    try{
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $filePath = $request->file('thumbnail')->store('thumbnails','public');
            $data['thumbnail'] = $filePath;
        }

        if ($request->has('save_as_draft')) {
            $data['published_at'] = null;
        }

        $data['author_id'] = auth()->id();

        $post = Post::create($data);

        if ($request->filled('tags')) {
            $post->tags()->attach($request->tags);
        }

        DB::commit();

        if ($post->published_at) {
            DispatchPostNotificationJob::dispatch($post->id);
        }

        if($post->published_at) {
            return redirect()->route('admin.posts.index')->with('success','Post published successfully!');
        }
        return redirect()->route('admin.posts.drafts')->with('warning','Post drafted successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e);

        return redirect()->route('admin.posts.index')
            ->with('error','Server issues. Try again later!');
    }
}


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories=Category::all();
        $tags=Tag::all();
        return view('admin.posts.edit',compact([
            'post',
            'categories',
            'tags'
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data=$request->validated();
        if($request->hasFile('thumbnail')) {
            Storage::disk('public')->delete($post->thumbnail);
            $data['thumbnail']=$request->file('thumbnail')->store('thumbnails','public');
        }
        $post->update($data);
        $post->tags()->sync($request->tags);

        return redirect()->route('admin.posts.index')
                        ->with('success','Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
            Storage::disk('public')->delete($post->thumbnail);
        }
        $post->tags()->detach();
        $post->delete();
        return redirect()->route('admin.posts.index')
            ->with('success', 'Post Deleted Successfully!');
    }

    public function uploadImage(Request $request) {
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('blog_image', 'public');

            return response()->json([
                'success' =>true,
                'url'=> asset('storage/' .$path)
            ]);
        }

        return response()->json(['success'=>false], 400);
    }

    private function generatePrompt($title, $excerpt) {
        return <<<PROMPT
            You are an expert content strategist and professional blog writer. Write a high-quality, authoritative, and engaging article based on the following:

            Title: "$title"
            Core Concept: "$excerpt"

            Writing Guidelines:
            1. Tone: Professional yet conversational, insightful, and persuasive. Avoid robotic or "childish" language.
            2. Structure:
            - Start with a hook-driven introduction that addresses a pain point or curiosity.
            - Use exactly 4-5 <h3> subheadings to break down the topic logically.
            - Use <ul> or <ol> for actionable steps or key takeaways.
            - Every paragraph should provide value and maintain flow.
            3. HTML Requirements:
            - Use ONLY <h3>, <h4>, <p>, <ul>, <li>, and <strong> tags.
            - NO Markdown (no ```), NO CSS, NO inline styles.
            4. Final Touch: End with a thought-provoking conclusion or a summary under an <h4> tag.

            Output ONLY the raw HTML content ready to be pasted into an editor.
            PROMPT;
    }


    public function generateAI(Request $request) {

        $user = auth()->user();
        Log::info($user->name);
        // $user = User::where('user_token',$token)->firstOrFail();

        if (!$user) {
            return response()->json(['content' => 'Unauthorized', 'status' => 401]);
        }

        $title = $request->title;
        $excerpt = $request->excerpt;

        $prompt = $this->generatePrompt($title, $excerpt);

        $client = new Client();
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent?key={$apiKey}";

        $payload = [
            'contents'=>[[
                'parts'=>[[
                    'text'=> $prompt
                ]]]
            ]
        ];

        $response = $client->post($url, ['json' => $payload, ['headers'=> ['Content-Type'=> 'application/json']]]);


        if($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody(), true);

            $activeSubscription = $user->activeSubscription();
            if ($activeSubscription && $activeSubscription->articles_remaining > 0) {
                $activeSubscription->decrement('articles_remaining');
            }

            $user->increment('articles_generated');

            $content = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if (!$content) {
                return response()->json(['content' => 'AI could not generate content at this time. Please try a different topic.', 'status' => 500]);
            }

            // Remove code fences like ```html or ```
            $content = preg_replace('/^```(?:html)?\s*/i', '', $content);
            $content = preg_replace('/```$/', '', $content);

            // Trim whitespace just in case
            $content = trim($content);

            return response()->json(['content' => $content, 'status' => 200]);

        }

        return response()->json(['content'=>'You have reached ur limit','status'=>403]);
    }


}
