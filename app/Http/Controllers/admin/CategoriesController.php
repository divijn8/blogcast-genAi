<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index() {
        $categories= Category::paginate(10);

        return view('admin.categories.index',compact([
            'categories'
        ]));
    }
}
