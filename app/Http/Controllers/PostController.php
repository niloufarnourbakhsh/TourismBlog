<?php

namespace App\Http\Controllers;

use App\Events\DeletePhoto;
use App\Events\InsertPhoto;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::query()->with('City')->paginate(5);
        return view('Admin.index')->with('posts', $posts);
    }

    public function create()
    {
        return view('Admin.create');
    }

    public function store(CreatePostRequest $request)
    {
        $post = $request->save();
        $images = $request->file;
        event(new InsertPhoto($post, $images));
        return redirect()->back();
    }

    public function edit(Post $post)
    {
        $post->with('photos');
        return view('Admin.edit')->with('post', $post);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $request->save();
        if ($images = $request->file) {
            event(new InsertPhoto($post, $images));
        }
        Session::flash('post-edition', 'تغییرات اعمال شد ');
        return redirect()->back();
    }

    public function all()
    {
        if (\request()->category) {
            $posts = Post::query()->with(['category'])
                ->where(['is_active' => 1])
                ->whereHas('category', function ($query) {
                    $query->where('name', request()->category);
                })->paginate(4);
        } else {
            $posts = Post::query()->where(['is_active' => 1])->with('photos')->paginate(4);
        }
        return view('Users.gallery')->with('posts', $posts);
    }

    public function show(Post $post)
    {
        $post->increment('view');
        $post->with(['city', 'photos', 'likes', 'comments']);
        $is_liked = $post->showLikesInPost();
        return view('Users.show-posts')->with('post', $post)->with('is_liked', $is_liked);
    }

    public function destroy(Post $post)
    {
        if ($post->photos()) {
            event(new DeletePhoto($post));
        }
        $post->city()->delete();
        $post->delete();
        Session::flash('post-deletion', 'پست مورد نظر حذف شد');
        return redirect()->back();

    }

    public function active(Request $request, Post $post)
    {
        $post->update(['is_active' => $request->is_active,]);
        return redirect()->back();
    }
}
