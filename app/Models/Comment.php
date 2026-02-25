<?php

namespace App\Models;

use App\Traits\LikeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
    use LikeTrait;
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

}
