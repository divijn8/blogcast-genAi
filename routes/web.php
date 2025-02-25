<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend.home');
});

//categories
Route::get('admin/categories', [\App\Http\Controllers\admin\CategoriesController::class, 'index'])->name('admin.categories.index');
Route::get('admin/categories/create', [\App\Http\Controllers\admin\CategoriesController::class, 'create'])->name('admin.categories.create');
Route::get('admin/categories/{category}/edit', [\App\Http\Controllers\admin\CategoriesController::class, 'edit'])->name('admin.categories.edit');

Route::post('admin/categories', [\App\Http\Controllers\admin\CategoriesController::class, 'store'])->name('admin.categories.store');

Route::put('admin/categories/{category}', [\App\Http\Controllers\admin\CategoriesController::class, 'update'])->name('admin.categories.update');

Route::delete('admin/categories/{category}', [\App\Http\Controllers\admin\CategoriesController::class, 'destroy'])->name('admin.categories.destroy');


//tags
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('tags', \App\Http\Controllers\admin\TagsController::class)->except(['show']);
    Route::resource('posts',\App\Http\Controllers\PostsController::class);
});


Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});
