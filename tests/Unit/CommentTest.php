<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_can_like_a_other_users_comment()
    {
        $this->signIn(Role::ROLE_USER);
        $post=Post::factory()->create();
       $comment= auth()->user()->comments()->create(['body'=>'new Comment','post_id'=>$post->id]);
       $comment->AddLike();
       $this->assertCount(1,Like::all());
    }
    /** @test */
    public function a_user_can_delete_their_likes()
    {
        $this->signIn(Role::ROLE_USER);
        $post=Post::factory()->create();
        $comment= auth()->user()->comments()->create(['body'=>'new Comment','post_id'=>$post->id]);
        $like=$comment->AddLike();
        $this->assertCount(1,Like::all());
        $comment->removeLike($like);
        $this->assertCount(0,Like::all());
    }

}
