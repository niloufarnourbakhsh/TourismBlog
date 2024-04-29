<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Notifications\PostLikeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PostLikeController extends Controller
{
    public function store(Post $post)
    {
        if ($post->likes()->count() === 0) {
            $post->likePost();
        } else {
            $like = $post->likes()->where(['user_id' => Auth::id()])->first();
            $like ? $post->takeLikeBack($like) : $post->likePost();
        }
        return redirect()->back();
    }
}
