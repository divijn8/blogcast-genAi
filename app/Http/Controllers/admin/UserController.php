<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact([
            'users'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        User::create($request->validated());
        return redirect()->route('admin.users.index')
            ->with('success', 'Users Created Successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact(['user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate(
            [
                     'name' => 'required|string|max:255,'. $user->id,
                     'email' => 'required|email|unique:users,email,'.$user->id
            ]
            );
            $user->update($validated);
            return redirect()->route('admin.users.index')
                        ->with('success',"User updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User Deleted Successfully!');
    }

    /**
     * Displaying some components in the dashboard for the user
     */
    public function dashboard(){
        $userId = Auth::id();
        $postCount = DB::table('posts')
                    ->where('author_id',$userId)
                    ->count();

        $totalViewCount = DB::table('posts')
                    ->where('author_id',$userId)
                    ->sum('view_count');

        $subscription = DB::table('subscriptions')
                    ->where('user_id', $userId)
                    ->where('status', 'active')
                    ->latest()
                    ->first();

        $articlesRemaining = $subscription ? $subscription->articles_remaining : '0';
        $subscriptionStatus = $subscription ? 'active' : 'inactive';
        $subscriptionPlan = null;
        if ($subscription) {
            $subscriptionPlan = DB::table('subscriptions')
                ->join('plans', 'plans.id', '=', 'subscriptions.plan_id')
                ->where('subscriptions.id', $subscription->id)
                ->select('plans.name')
                ->first();
        }

        $mostUsedTags= DB::table('post_tag')
                     ->join('tags', 'post_tag.tag_id', '=', 'tags.id')
                     ->join('posts', 'post_tag.post_id', '=', 'posts.id')
                     ->where('posts.author_id', $userId)
                     ->select('tags.name', DB::raw('count(*) as count'))
                     ->groupBy('tags.name')
                     ->orderByDesc('count')
                     ->limit(5)
                     ->get();

        $mostUsedCategories= DB::table('posts')
                     ->where('posts.author_id',$userId)
                     ->join('categories', 'posts.category_id', '=', 'category_id')
                     ->select('categories.name', DB::raw('count(*) as count'))
                     ->groupBy('categories.name')
                     ->orderByDesc('count')
                     ->limit(5)
                     ->get();

        $totalArticles= DB::table('subscriptions')
        ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
        ->where('subscriptions.user_id', $userId)
        ->where('subscriptions.status', 'active')
        ->select('plans.articles_per_month')
        ->first();

        return view('admin.dashboard', compact('postCount', 'totalViewCount', 'articlesRemaining','subscriptionStatus','mostUsedCategories','mostUsedTags','subscriptionPlan','totalArticles'));
    }
}
