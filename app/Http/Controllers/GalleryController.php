<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function __invoke()
    {
        if (\request()->category) {
            $posts = Post::query()
                ->where(['is_active' => 1])
                ->whereHas('category', function ($query) {
                    $query->where('name', request()->category);
                })->with(['photos','city'])->paginate(4);
        } else {
            $posts = Post::query()->where(['is_active' => 1])->with(['photos','city'])->paginate(4);
        }
        return view('Users.gallery')->with('posts', $posts);
    }
}
