<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Post $post=null,Comment $comment=null)
    {
        $model=$post??$comment;
        if ($model->likes()->count() === 0) {
            $model->AddLike();
        } else {
            $like = $model->likes()->where(['user_id' => Auth::id()])->first();
            $like ? $model->removeLike($like) : $model->AddLike();
        }
        return redirect()->back();
    }
}
