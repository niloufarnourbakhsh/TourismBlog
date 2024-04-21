<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Notifications\PostLikeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class LikeController extends Controller
{
    public function store(Post $post)
    {
        if ($post->likes()->count() === 0) {
            $like = $post->likes()->create(['user_id' => Auth::id()]);
            Notification::send($post->user, new PostLikeNotification(\auth()->user(), $like, $post));
        } else {
            $like = $post->likes()->where(['user_id' => Auth::id()])->first();
            $like ? $post->takeLikeBack($like) : $post->likePost();
        }
        return redirect()->back();
    }
}
