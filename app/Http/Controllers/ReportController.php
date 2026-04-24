<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Podcast;

class ReportController extends Controller
{
    public function report(Request $request)
    {
        $request->validate([
            'type' => 'required|in:post,podcast',
            'id' => 'required|integer',
            'reason' => 'required|string',
            'description' => 'nullable|string'
        ]);

        // find model
        $model = $request->type === 'post'
            ? Post::findOrFail($request->id)
            : Podcast::findOrFail($request->id);

        // prevent duplicate report
        $alreadyReported = $model->reports()
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadyReported) {
            return response()->json([
                'message' => 'You already reported this.'
            ], 400);
        }

        // save report
        $model->reports()->create([
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'description' => $request->description
        ]);

        // trigger moderation logic
        $model->handleNewReport();

        return response()->json([
            'message' => 'Reported successfully'
        ]);
    }

    public function index()
    {
        $posts = \App\Models\Post::with('reports.user')
            ->where('report_count', '>', 0)
            ->latest()
            ->get();

        $podcasts = \App\Models\Podcast::with('reports.user')
            ->where('report_count', '>', 0)
            ->latest()
            ->get();

        return view('admin.reports.index', compact('posts', 'podcasts'));
    }

    public function approve($type, $id)
    {
        $model = $type === 'post'
            ? \App\Models\Post::findOrFail($id)
            : \App\Models\Podcast::findOrFail($id);

        $model->update([
            'status' => 'active',
            'is_disabled' => false,
            'report_count' => 0
        ]);

        $model->reports()->delete();

        return back()->with('success', 'Content approved');
    }

    public function disable($type, $id)
    {
        $model = $type === 'post'
            ? \App\Models\Post::findOrFail($id)
            : \App\Models\Podcast::findOrFail($id);

        $model->update([
            'status' => 'disabled',
            'is_disabled' => true
        ]);

        return back()->with('error', 'Content disabled');
    }
}
