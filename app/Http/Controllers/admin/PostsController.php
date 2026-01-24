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
        $posts=$user->posts()->with('category')->with('tags')->latest()->paginate(20);
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
    public function store(CreatePostRequest $request)
    {
        DB::beginTransaction();

        try{
            $data = $request->validated();
            if($request->hasFile('thumbnail')) {
                $filePath = $request->file('thumbnail')->store('thumbnails','public');
                $data['thumbnail']=$filePath;
            }

            $data['author_id']= auth()->id();

            $post=Post::create($data);
            $post->tags()->attach($request->tags);

            DB::commit();

            DispatchPostNotificationJob::dispatch($post->id);
            return redirect()->route('admin.posts.index')
                             ->with('success','Post created successfully!');
        }catch(\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('admin.posts.index')
                             ->with('error','Server isuues.Try again later!');
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
    $prompt = <<<PROMPT
You are a friendly and creative blog writer. Write a **simple, clear, and engaging HTML blog article** based on the following details:

Title: "$title"
Excerpt: "$excerpt"

Instructions:
* Write in a natural, easy-to-read tone — like telling a story to a friend.
* Use **simple words and short sentences**. Avoid complex or formal vocabulary.
* Do **not** include the title or excerpt in the article body.
* Keep the article lively, relatable, and interesting — readers should enjoy reading it till the end.
* Use proper HTML structure:
  - Wrap main sections in `<h3>` tags (at least 4 of them).
  - Use `<p>` for paragraphs, `<ul>` and `<li>` for lists when needed.
  - Avoid Markdown code blocks (no ```html).
  - Do **not** include any CSS or JavaScript.
* Start with a short, catchy introduction that connects with the reader emotionally.
* Focus closely on the given title and excerpt theme.
* End with a short summary or reflection inside an `<h4>` tag.

Keep the tone **friendly, conversational, and positive**.
PROMPT;

    return $prompt;
}


    public function generateAI(Request $request,$token) {
        $user = User::where('user_token',$token)->firstOrFail();
        if(!$user->canGenerateArticle()){
            return response()->json(['content'=>'You have reached ur limit','status'=>401]);
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

//        Log::info(json_encode($payload));
        $response = $client->post($url, ['json' => $payload, ['headers'=> ['Content-Type'=> 'application/json']]]);
        Log::info("Status Code: " . $response->getStatusCode());
        if($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody(), true);
            Log::info($responseData['candidates'][0]['content']['parts'][0]['text']);

            if ($user->canGenerateArticle()) {
                $activeSubscription = $user->activeSubscription();
               if($activeSubscription) {
                $activeSubscription->decrement('articles_remaining');
               }
            }

            $user->increment('articles_generated');
            $content = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '';

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
