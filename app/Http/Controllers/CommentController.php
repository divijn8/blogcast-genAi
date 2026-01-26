<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentsRequest;
use App\Models\Comments;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function index()
    {
        $comments = Comments::with('post')->latest()->get();
        return view('admin.posts.comments', compact(['comments']));
    }


    public function store(CreateCommentsRequest $request, int $id)
    {
        $blog = Post::findOrFail($id);

        $commentData = [
            'post_id' => $id,
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ];

        if (auth()->check()) {
            $commentData = array_merge([
                'user_id' => auth()->id(),
            ], $commentData);

            if (auth()->user()->isAdmin() || auth()->user()->isOwner($blog)) {
                $commentData['approved_by'] = auth()->id();
            }

            session()->flash('success', 'Comment added successfully!');

        } else {
            $commentData = array_merge([
                'guest_name' => $request->name,
                'guest_email' => $request->email
            ], $commentData);

            session()->flash('success', 'Comment will be added once approved!');
        }

        Comments::create($commentData);
        return redirect()->back();
    }

    public function approve(Comments $comment)
    {
        $comment->approved_by = auth()->id();
        $comment->save();

        session()->flash('success', 'Comment approved...');
        return redirect(route('admin.posts.comments'));
    }

    public function unapprove(Comments $comment)
    {
        $comment->approved_by = null;
        $comment->save();

        session()->flash('error', 'Comment Unapproved...');
        return redirect(route('admin.posts.comments'));
    }

}
