<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Podcast;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy("id", "desc")->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        User::create($request->validated());
        return redirect()->route('admin.users.index')
            ->with('success', 'Users Created Successfully!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "User updated Successfully");
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User Deleted Successfully!');
    }

    public function dashboard()
    {
        $userId = Auth::id();

        // 🔹 BLOG BASIC (YOUR ORIGINAL)
        $postCount = DB::table('posts')
            ->where('author_id', $userId)
            ->count();

        $totalViewCount = DB::table('posts')
            ->where('author_id', $userId)
            ->sum('view_count');

        // 🔹 SUBSCRIPTION (YOUR ORIGINAL)
        $subscription = DB::table('subscriptions')
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->latest()
            ->first();

        $articlesRemaining = $subscription ? $subscription->articles_remaining : 0;
        $subscriptionStatus = $subscription ? 'active' : 'inactive';

        $subscriptionPlan = null;
        if ($subscription) {
            $subscriptionPlan = DB::table('subscriptions')
                ->join('plans', 'plans.id', '=', 'subscriptions.plan_id')
                ->where('subscriptions.id', $subscription->id)
                ->select('plans.name')
                ->first();
        }

        $totalArticles = DB::table('subscriptions')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->where('subscriptions.user_id', $userId)
            ->where('subscriptions.status', 'active')
            ->select('plans.articles_per_month')
            ->first();

        // 🔹 TAGS & CATEGORIES (YOUR ORIGINAL)
        $mostUsedTags = DB::table('taggables')
            ->join('tags', 'taggables.tag_id', '=', 'tags.id')
            ->join('posts', 'taggables.taggable_id', '=', 'posts.id')
            ->where('taggables.taggable_type', 'App\Models\Post')
            ->where('posts.author_id', $userId)
            ->select('tags.name', DB::raw('count(*) as count'))
            ->groupBy('tags.id', 'tags.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $mostUsedCategories = DB::table('posts')
            ->where('posts.author_id', $userId)
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as count'))
            ->groupBy('categories.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // 🔥 ================= NEW LOGIC =================

        // BLOG ADVANCED
        $topBlogs = Post::where('author_id', $userId)
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        $recentBlogs = Post::where('author_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        $blogReports = DB::table('posts')
            ->where('author_id', $userId)
            ->sum('report_count');

        // PODCAST DATA (USER SPECIFIC)
        $podcastCount = Podcast::where('author_id', $userId)->count();

        $podcastViews = Podcast::where('author_id', $userId)->sum('view_count');

        $topPodcasts = Podcast::where('author_id', $userId)
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        $recentPodcasts = Podcast::where('author_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        $podcastReports = DB::table('podcasts')
            ->where('author_id', $userId)
            ->sum('report_count');

        // 🔹 KEEP THIS (YOUR ORIGINAL)
        $podcastStats = [
            'total_episodes' => Podcast::count(),
            'total_views' => Podcast::sum('view_count'),
            'top_category' => Category::where('type', 'podcast')
                ->withCount('podcasts')
                ->orderBy('podcasts_count', 'desc')
                ->first(),
            'fav_blog_cat' => Category::where('type', 'blog')
                ->withCount('posts')
                ->orderBy('posts_count', 'desc')
                ->first(),
        ];

        // 1. Engagement Metrics
        $avgBlogViews = $postCount > 0 ? ceil($totalViewCount / $postCount) : 0;
        $avgPodcastViews = $podcastCount > 0 ? ceil($podcastViews / $podcastCount) : 0;

        // 2. Storage Estimation (Assuming 1 Podcast = ~5MB on average)
        $estimatedStorageMB = $podcastCount * 5;
        $storageLimitMB = 100;
        $storagePercentage = min(($estimatedStorageMB / $storageLimitMB) * 100, 100);

        // 3. Content Growth Trend (Last 6 Months)
        $months = [];
        $blogGrowthData = [];
        $podcastGrowthData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M'); // e.g., 'Nov', 'Dec', 'Jan'

            $blogGrowthData[] = Post::where('author_id', $userId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $podcastGrowthData[] = Podcast::where('author_id', $userId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return view('admin.dashboard', compact(
            // OLD
            'postCount',
            'totalViewCount',
            'articlesRemaining',
            'subscriptionStatus',
            'mostUsedCategories',
            'mostUsedTags',
            'subscriptionPlan',
            'totalArticles',
            'podcastStats',

            // NEW
            'topBlogs',
            'recentBlogs',
            'blogReports',
            'podcastCount',
            'podcastViews',
            'topPodcasts',
            'recentPodcasts',
            'podcastReports',
            'avgBlogViews', 'avgPodcastViews',
            'estimatedStorageMB', 'storageLimitMB', 'storagePercentage',
            'months', 'blogGrowthData', 'podcastGrowthData'
        ));
    }
}
