<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreateRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

class CommentController extends Controller
{
    public function store(CommentCreateRequest $request,Post $post)
    {
        Auth::user()->comments()->create([
            'body'=>$request->body,
            'post_id'=>$post->id
        ]);
        Notification::send($post->user,new CommentNotification(Auth::user()));
        return redirect()->back();
    }

    public function delete(Comment $comment)
    {
        $comment->delete();
        Session::flash('delete_message', 'پیام موورد نظر حذف شد');
        return redirect()->back();
    }

    public function likeStore(Comment $comment)
    {
        if ($comment->likes()->count()===0){

            $comment->likes()->create(['user_id'=>Auth::id()]);
        }else{
            $comment->likes()->where(['user_id'=>Auth::id()])->first()?
                $comment->likes()->where(['user_id'=>Auth::id()])->delete():
                $comment->likes()->create(['user_id'=>Auth::id()]);

        }
        return redirect()->back();
    }

}
