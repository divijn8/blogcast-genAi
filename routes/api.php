<?php

use App\Http\Controllers\admin\PostsController;
use Illuminate\Support\Facades\Route;

Route::post('/posts/upload-image', [PostsController::class, 'uploadImage'])->name('posts.uploadImage');
