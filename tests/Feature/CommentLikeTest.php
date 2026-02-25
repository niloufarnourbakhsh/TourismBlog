<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentLikeTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function users_can_like_others_comment()
    {
        $post=Post::factory()->create();
        $this->signIn("Admin");
        $this->post('/comment/'.$post->id,[
            'body'=>'hiiiiiiiiiii'
        ]);
        $comment=Comment::first();
        $this->post('/comment/like/'.$comment->id);
        $this->assertCount(1,Like::all());

    }
    /** @test */
    public function users_can_take_their_likes_of_other_users_comments_back()
    {
        $post=Post::factory()->create();
        $this->assertCount(1,Post::all());
        $this->signIn('Admin');
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
