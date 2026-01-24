<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function index()
    {
        if(auth()->user()->isAdmin()) {
            $comments = Comments::with('blog')->latest()->paginate(10);
        }

        return view('admin.comments.index', compact(['comments']));
    }

    public function store(Request $request){
        $this->validate($request,[
            'content'=>'required|string|min:2|max:1000',
            'post_id'=>'required|exists:posts,id',
            'parent_id'=>'nullable|exists:comments,id'
        ]);

        Comments::create([
            'content'=>$request->content,
            'post_id'=>$request->post_id,
            'user_id'=>auth()->id(),
            'parent_id'=>$request->parent_id,
        ]);

        return redirect()->back();
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'content'=>'required|string|min:2|max:1000',
            'post_id'=>'required|exists:posts,id',
            'parent_id'=>'nullable|exists:comments,id'
        ]);

        $commentData = [
            'post_id' => $request->post_id,
            'content' => $request->comment,
        ];

        if (auth()->check()) {
            $commentData = array_merge([
                'commented_by' => auth()->id(),
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
    }
}
