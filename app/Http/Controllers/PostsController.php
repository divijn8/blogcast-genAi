<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts=Post::with('category')->with('tags')->latest()->paginate(20);
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
            $post=Post::create($data);
            $post->tags()->attach($request->tags);

            DB::commit();
            return redirect()->route('admin.posts.index')
                             ->with('success','Post created successfully');
        }catch(\Exception $e) {
            DB::rollBack();
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
}
