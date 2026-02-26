<?php

namespace App\Http\Controllers;

use App\Events\DeletePhoto;
use App\Events\InsertPhoto;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\City;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    public function index()
    {
//       $posts= Cache::remember('posts',now()->addMinute(5),function (){
//            Post::query()->with('City')->paginate(4);
//        });
       $posts=Post::query()->with('City')->paginate(4);

        return view('Admin.index')->with('posts', $posts);
    }

    public function create()
    {
        return view('Admin.create');
    }

    public function store(CreatePostRequest $request)
    {
        $post=auth()->user()->posts()->create([
            'title'=>$request->title,
            'body'=>$request->body,
            'city_id'=>$request->city_id,
            'category_id'=>$request->category_id,
            'food'=>$request->food,
            'touristAttraction'=>$request->touristAttraction
        ]);
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
        tap($post->update($request->except(['file'])));;
        if ($images = $request->file) {
            event(new InsertPhoto($post, $images));
        }
        Session::flash('post-edition', 'تغییرات اعمال شد ');
        return redirect()->back();
    }


    public function show(Post $post)
    {
        Redis::incr('view.'.$post->id);
        $view=Redis::get('view.'.$post->id);
        $post->with(['city', 'photos', 'likes', 'comments']);
        $is_liked = $post->showLikesInPost();
        return view('Users.show-posts')->with('post', $post)->with('is_liked', $is_liked)->with('view',$view);
    }

    public function destroy(Post $post)
    {
        if ($post->photos()) {
            event(new DeletePhoto($post));
        }
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
