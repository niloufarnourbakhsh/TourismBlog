<?php

namespace App\Traits;

use App\Events\DeleteNotificationEvent;
use App\Models\Like;
use App\Models\Post;
use App\Notifications\PostLikeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

trait LikeTrait
{
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    public function AddLike()
    {
        $like=$this->likes()->create(['user_id' => Auth::id()]);
        if ($this instanceof Post){
            Notification::send($this->user, new PostLikeNotification(\auth()->user(), $like, $this));
        }
        return $like;
    }
    public function removeLike($like)
    {
        if ($this instanceof Post){
            event(new DeleteNotificationEvent($this, $like));
        }
        return $this->likes()->where(['user_id' => Auth::id()])->delete();
    }
}
