<?php

namespace App\Models;


use App\Traits\LikeTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    use HasFactory;
    use Sluggable;
    use LikeTrait;

    protected $guarded;
    protected $with = ['photos'];
    protected $casts = ['is_active' => 'boolean'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function path()
    {
        return '/posts/' . $this->id;
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
        if (auth()->check()) {
            return $this->likes()->where(['user_id' => auth()->id()])->first() ? true : false;
        } else {
            return false;
        }
    }

    public function updateCity($city)
    {
        return City::whereId($this->city_id)->update(['name' => $city]);
    }

}
