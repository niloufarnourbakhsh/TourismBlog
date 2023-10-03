<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Notifications\PostLikeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class likeTest extends TestCase
{
    use RefreshDatabase;
    public $user;
    public function setUp():void
    {
       parent::setUp();
        $role=Role::create(['name'=>'User']);
       $this->user =User::factory()->create(['role_id'=>$role->id]);
    }
    /** @test */
    public function a_user_can_like_a_post()
    {
        $post=Post::factory()->create();
        $this->assertCount(1,Post::all());
        $this->actingAs($this->user)->post('/posts/like/'.$post->id);
        $this->assertCount(1,Like::all());
    }

    /** @test */
    public function check_the_notification_works()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $post=Post::factory()->create();
        $this->assertCount(1,Post::all());
        $this->actingAs($this->user)->post('/posts/like/'.$post->id);
        $this->assertCount(1,Like::all());
        Notification::assertSentTo($this->user,PostLikeNotification::class);
        Notification::assertCount(1);
    }
    /** @test */
    public function just_logged_in_user_can_like_a_post()
    {
        $post=Post::factory()->create();
        $this->assertCount(1,Post::all());
        $this->post('/posts/like/'.$post->id)->assertRedirect('/login');
    }
    /** @test */
    public function unlike_a_post()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $post=Post::factory()->create();
        $this->assertCount(1,Post::all());
        $this->actingAs($this->user)->post('/posts/like/'.$post->id);
        $this->assertCount(1,Like::all());
        Notification::assertSentTo($this->user,PostLikeNotification::class);
        Notification::assertCount(1);
        $this->actingAs($this->user)->post('/posts/like/'.$post->id);
    }

    /** @test */
    public function like_a_comment()
    {
        $post=Post::factory()->create();
        $this->assertCount(1,Post::all());
        $this->actingAs($this->user)->post('/comment/'.$post->id,[
            'body'=>'hiiiiiiiiiii'
        ]);
        $this->assertCount(1,Comment::all());
        $comment=Comment::first();
        $this->actingAs($this->user)->post('/comment/like/'.$comment->id);
        $this->assertCount(1,Like::all());

    }

    /** @test */
    public function unlike_a_comment()
    {
        $post=Post::factory()->create();
        $this->assertCount(1,Post::all());
        $this->actingAs($this->user)->post('/comment/'.$post->id,[
            'body'=>'hiiiiiiiiiii'
        ]);
        $this->assertCount(1,Comment::all());
        $comment=Comment::first();
        $this->actingAs($this->user)->post('/comment/like/'.$comment->id);
        $this->assertCount(1,Like::all());
        $this->actingAs($this->user)->post('/comment/like/'.$comment->id);
        $this->assertCount(0,Like::all());
    }
}
