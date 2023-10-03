<?php

namespace App\Http\Controllers;

use App\Events\DeleteNotificationEvent;
use App\Events\DeletePhoto;
use App\Events\InserPhoto;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\City;
use App\Models\Post;
use App\Notifications\PostLikeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use function Laravel\Prompts\text;

class PostController extends Controller
{
    public function index()
    {
        $posts=Post::query()->with('City')->paginate(5);
        return view('Admin.index')->with('posts',$posts);
    }

    public function create()
    {
        return view('Admin.create');
    }
    public function store(CreatePostRequest $request)
    {
        $data=array_merge($request->only('title','body','food','touristAttraction','category_id'),[
            'user_id'=>Auth::id(),
            'city_id'=>(City::create(['name'=>$request->city]))->id
        ]);
        $post=Post::create($data);
        $images=$request->file;
        event(new InserPhoto($post,$images));
        return redirect()->back();
    }

    public function edit(Post $post)
    {
        $post->with('photos');
        return view('Admin.edit')->with('post',$post);
    }

    public function update(UpdatePostRequest $request,Post $post)
    {
        City::whereId($request->cityId)->update(['name'=>$request->city]);
        $post->update([
            'title'=>$request->title,
            'body'=>$request->body,
            'food'=>$request->food,
            'category_id'=>$request->category_id,
            'touristAttraction'=>$request->touristAttraction,
        ]);

        if ($images=$request->file){
            event(new InserPhoto($post,$images));
        }
        Session::flash('post-edition','تغییرات اعمال شد ');
        return redirect()->back();
    }

    public function all()
    {
        if (\request()->category){

            $posts=Post::query()->with(['category','photos'])
                ->where(['is_active'=>1])
                ->whereHas('category',function ($query){
                    $query->where('name', request()->category);
                })->paginate(4);

        }else{
            $posts=Post::query()->where(['is_active'=>1])->with('photos')->paginate(4);
        }
        return view('Users.gallery')->with('posts',$posts);
    }
    public function show(Post $post)
    {
        $post->increment('view');
        $post->with(['city','photos','likes','comments']);
        $is_liked=false;
        if (Auth::check()){
            $is_liked=$post->likes()->where(['user_id'=>Auth::id()])->first()?true :false;
        }
        return view('Users.show-posts')->with('post',$post)->with('is_liked',$is_liked);
    }

    public function storeLikes(Post $post)
    {
        if ($post->likes()->count()===0) {
            $like=$post->likes()->create(['user_id' => Auth::id()]);

            Notification::send($post->user, new PostLikeNotification(\auth()->user(),$like, $post));
        }else{

            $like=$post->likes()->where(['user_id'=>Auth::id()])->first();
                $like?
                event(new DeleteNotificationEvent($post,$like))
                &&
                $post->likes()->where(['user_id'=>Auth::id()])->delete()
                :
                $post->likes()->create(['user_id'=>Auth::id()]);
                Notification::send($post->user,new PostLikeNotification(\auth()->user(),$like,$post));

        }

        return redirect()->back();
    }
    public function destroy(Post $post)
    {
        if ($post->photos()){
            event(new DeletePhoto($post));
        }
        $post->city()->delete();
        $post->delete();
        Session::flash('post-deletion','پست مورد نظر حذف شد');
        return redirect()->back();

    }
    public function active(Request $request, Post $post)
    {
        $post->update([
            'is_active'=>$request->is_active,
        ]);
        return redirect()->back();
    }
}
