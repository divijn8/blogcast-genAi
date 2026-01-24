<?php

use App\Http\Controllers\BlogsController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//blogs -> frontend
Route::get('/', [\App\Http\Controllers\BlogsController::class, 'blogs'])->name('frontend.home');
Route::get('/blogs/{slug}', [\App\Http\Controllers\BlogsController::class, 'show'])->name('frontend.show');
Route::get('/blogs/categories/{category}', [\App\Http\Controllers\BlogsController::class, 'showByCategory'])->name('frontend.showByCategory');
Route::get('/blogs/tags/{tag}', [\App\Http\Controllers\BlogsController::class, 'showByTag'])->name('frontend.showByTag');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    //for tags
    Route::resource('tags', \App\Http\Controllers\admin\TagsController::class)->except(['show']);
    //for posts
    Route::resource('posts', \App\Http\Controllers\admin\PostsController::class);
    //for users
    Route::resource('users',\App\Http\Controllers\admin\UserController::class);

    Route::get('/blogs/drafts', [BlogsController::class, 'drafts'])->name('posts.drafts');

    Route::put('/blogs/{blog}/publish',[BlogsController::class, 'publishBlog'])->name('posts.publish');

    //for categories
    Route::get('categories', [\App\Http\Controllers\admin\CategoriesController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [\App\Http\Controllers\admin\CategoriesController::class, 'create'])->name('categories.create');
    Route::get('categories/{category}/edit', [\App\Http\Controllers\admin\CategoriesController::class, 'edit'])->name('categories.edit');
    Route::post('categories', [\App\Http\Controllers\admin\CategoriesController::class, 'store'])->name('categories.store');
    Route::put('categories/{category}', [\App\Http\Controllers\admin\CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [\App\Http\Controllers\admin\CategoriesController::class, 'destroy'])->name('categories.destroy');

    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');

    Route::put('/comments/{comment}/approve',[CommentController::class, 'approve'])->name('comments.approve');
    Route::put('/comments/{comment}/unapprove',[CommentController::class, 'unapprove'])->name('comments.unapprove');

    Route::get('dashboard',
        [\App\Http\Controllers\admin\UserController::class,'dashboard']
    )->name('dashboard');
});

Route::middleware(['auth'])->group((function() {

    Route::post('/generate-ai', [\App\Http\Controllers\admin\PostsController::class, 'generateAI']);
    Route::get('/subscriptions',[\App\Http\Controllers\admin\SubscriptionController::class,'showPlans'])->name('subscriptions.index');
    Route::get('/subscriptions/{planId}/checkout',[\App\Http\Controllers\admin\SubscriptionController::class,'createCheckoutSession'])->name('subscription.checkout');
    Route::get('/subscriptions/success/{planId}',[\App\Http\Controllers\admin\SubscriptionController::class,'success'])->name('subscription.success');
    Route::get('/subscriptions/cancel',[\App\Http\Controllers\admin\SubscriptionController::class,'cancel'])->name('subscription.cancel');
}));

Route::post('/posts/{post}/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');

Auth::routes();

