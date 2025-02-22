<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index() {
        $categories= Category::latest()->paginate(10);

        return view('admin.categories.index',compact([
            'categories'
        ]));
    }

    public function create() {
        return view('admin.categories.create');
    }

    public function store(Request $request){
        $validated = $request->validate(
            [
                'name'=>'required|unique:categories|max:255|min:2'
            ]
            );
            Category::create($validated);
            return redirect()->route('admin.categories.index')
                        ->with('success',"Category created Successfully");
    }

    public function edit(Request $request, Category $category){
        return view('admin.categories.edit',compact([
            'category'
        ]));
    }

    public function update(Request $request,Category $category){
        $validated = $request->validate(
            [
                'name'=>'required|max:255|min:2|unique:categories,name,'.$category->id
            ]
            );
            $category->update($validated);
            return redirect()->route('admin.categories.index')
                        ->with('success',"Category updated Successfully");
    }
}
