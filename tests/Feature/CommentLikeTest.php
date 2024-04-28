<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentLikeTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function like_a_comment()
    {
        $post=Post::factory()->create();
        $this->assertCount(1,Post::all());
        $this->signeIn();
        $this->post('/comment/'.$post->id,[
            'body'=>'hiiiiiiiiiii'
        ]);
        $this->assertCount(1,Comment::all());
        $comment=Comment::first();
        $this->post('/comment/like/'.$comment->id);
        $this->assertCount(1,Like::all());

    }
    /** @test */
    public function unlike_a_comment()
    {
        $post=Post::factory()->create();
        $this->assertCount(1,Post::all());
        $this->signeIn();
        $this->post('/comment/'.$post->id,[
            'body'=>'hiiiiiiiiiii'
        ]);
        $this->assertCount(1,Comment::all());
        $comment=Comment::first();
        $this->post('/comment/like/'.$comment->id);
        $this->assertCount(1,Like::all());
        $this->post('/comment/like/'.$comment->id);
        $this->assertCount(0,Like::all());
    }
}
