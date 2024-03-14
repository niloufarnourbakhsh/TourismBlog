<?php

namespace App\Models;

use App\Events\DeleteNotificationEvent;
use App\Notifications\PostLikeNotification;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class Post extends Model
{
    use HasFactory;
    use Sluggable;
    protected $guarded;
    protected $with=['photos'];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function photos()
    {
       return $this->hasMany(Photo::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class,'likeable');
    }

    public function comments()
    {
       return $this->hasMany(Comment::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function showLikesInPost()
    {
        if (auth()->check()){
            return $this->likes()->where(['user_id'=>auth()->id()])->first()?true: false;
        }else{
            return false;
        }
    }

    public function likePost()
    {
        $like=$this->likes()->create(['user_id'=>Auth::id()]);
        Notification::send($this->user,new PostLikeNotification(\auth()->user(),$like,$this));
    }

    public function takeLikeBack($like)
    {
        event(new DeleteNotificationEvent($this,$like))
        &&
        $this->likes()->where(['user_id'=>Auth::id()])->delete();
    }

}
