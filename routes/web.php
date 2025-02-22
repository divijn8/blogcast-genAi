<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin/categories', [\App\Http\Controllers\admin\CategoriesController::class, 'index'])->name('admin.categories.index');
Route::post('admin/categories', [\App\Http\Controllers\admin\CategoriesController::class, 'store'])->name('admin.categories.store');
Route::get('admin/categories/create', [\App\Http\Controllers\admin\CategoriesController::class, 'create'])->name('admin.categories.create');
Route::get('admin/categories/{category}/edit', [\App\Http\Controllers\admin\CategoriesController::class, 'edit'])->name('admin.categories.edit');
Route::put('admin/categories/{category}', [\App\Http\Controllers\admin\CategoriesController::class, 'update'])->name('admin.categories.update');
Route::delete('admin/categories/{category}', [\App\Http\Controllers\admin\CategoriesController::class, 'destroy'])->name('admin.categories.destroy');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});
