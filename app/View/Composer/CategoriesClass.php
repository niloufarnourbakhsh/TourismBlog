<?php

namespace App\View\Composer;
use App\Models\Category;
use Illuminate\View\View;
class CategoriesClass
{
    public function compose(View $view)
    {
        $view->with('categories',Category::all());
    }
}
