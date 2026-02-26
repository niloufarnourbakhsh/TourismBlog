<?php

namespace App\Http\Controllers\Api;

use App\Events\DeletePhoto;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    public function index()
    {
        return new PostCollection(Post::paginate(10));
    }

    public function show(Post $post)
    {

        Redis::incr('view.'.$post->id);
        $view=Redis::get('view.'.$post->id);
        $post->load('city','photos','likes','comments');
        return new PostResource($post);
    }

    public function create()
    {

    }

    public function update()
    {

    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete',$post);
        if ($post->photos()->exists()) {
            event(new DeletePhoto($post));
        }
        $post->delete();
        return response()->noContent();
    }
}
