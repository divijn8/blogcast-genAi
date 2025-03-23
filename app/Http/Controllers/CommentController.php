<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;

class CommentController extends Controller
{
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
}
