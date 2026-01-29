<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentsRequest;
use App\Models\Comments;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Admin: List all comments (blogs + podcasts)
     */
    public function index()
    {
        $comments = Comments::with(['commentable', 'user', 'parent'])
            ->latest()
            ->get();

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Frontend: Store comment or reply (polymorphic)
     */
    public function store(CreateCommentsRequest $request)
    {
        $typeMap = [
            'post'    => \App\Models\Post::class,
            'podcast' => \App\Models\Podcast::class,
        ];

        // dd($request);
        if (! isset($typeMap[$request->commentable_type])) {
            abort(400, 'Invalid comment type');
        }

        $commentableClass = $typeMap[$request->commentable_type];
        $commentable = $commentableClass::findOrFail($request->commentable_id);

        $data = [
            'comment'   => $request->comment,
            'parent_id' => $request->parent_id ?? null,
        ];

        if (auth()->check()) {
            $data['user_id'] = auth()->id();

            if (auth()->user()->isAdmin()) {
                $data['approved_by'] = auth()->id();
            }
        } else {
            $data['guest_name']  = $request->guest_name;
            $data['guest_email'] = $request->guest_email;
        }

        $commentable->comments()->create($data);

        return back()->with('success', 'Comment submitted successfully');
    }


    /**
     * Admin: Approve comment
     */
    public function approve(Comments $comment)
    {
        $comment->update([
            'approved_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.comments.index')
            ->with('success', 'Comment approved');
    }

    /**
     * Admin: Unapprove comment
     */
    public function unapprove(Comments $comment)
    {
        $comment->update([
            'approved_by' => null,
        ]);

        return redirect()
            ->route('admin.comments.index')
            ->with('success', 'Comment unapproved');
    }
}
