<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;

    protected $guarded;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Comment::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function AddLike()
    {
        if ($this->likes()->count() === 0) {
            return $this->likes()->create(['user_id' => Auth::id()]);
        } else {
            return $this->likes()->where(['user_id' => Auth::id()])->first() ?
                $this->likes()->where(['user_id' => Auth::id()])->delete() :
                $this->likes()->create(['user_id' => Auth::id()]);
        }
    }
}
