<?php

use Illuminate\Support\Facades\Route;

Route::post('/posts/{token}/generate-ai', [\App\Http\Controllers\admin\PostsController::class, 'generateAI'])->name('admin.posts.generateAI'); // TODO: Add middleware auth so this route is protected after REST project
Route::post('/posts/upload-image', [\App\Http\Controllers\admin\PostsController::class, 'uploadImage'])->name('posts.uploadImage');
