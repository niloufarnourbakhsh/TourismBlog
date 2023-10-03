<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories=Category::all();
        return view('Admin.category')->with('categories',$categories);
    }

    public function store(CreateCategoryRequest $request)
    {
        Category::create(['name'=>$request->name]);
        return redirect()->back();
    }

    public function update(CreateCategoryRequest $request,Category $category)
    {
        $category->update(['name'=>$request->name]);
        return redirect()->back();
    }

    public function destroy(Category $category)
    {
    $category->delete();
    return redirect()->back();
    }
}
