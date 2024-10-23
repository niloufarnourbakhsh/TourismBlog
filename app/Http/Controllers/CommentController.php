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
    public function store(CommentCreateRequest $request, Post $post)
    {
        $comment=$request->saveComment();
        Notification::send($post->user, new CommentNotification(Auth::user(),$comment,$post));
        return redirect()->back();
    }

    public function delete(Comment $comment)
    {
        $comment->delete();
        Session::flash('delete_message', 'پیام موورد نظر حذف شد');
        return redirect()->back();
    }

}
