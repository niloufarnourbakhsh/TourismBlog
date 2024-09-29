<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PostCommentTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_can_leave_a_comment()
    {
        $this->userSigneIN();
        $post=Post::factory()->create();
        $this->post('/comment/'.$post->id,[
            'body'=>'new Comment'
        ]);
        $this->assertCount(1,Comment::all());
    }
    /** @test */
    public function a_notification_will_send_to_admin_after_leaving_a_comment()
    {
        $post=Post::factory()->create(['user_id'=>$this->signeIn()->id]);
        Notification::fake();
        Notification::assertNothingSent();
        $this->userSigneIN();
        $this->post('/comment/'.$post->id,$comment=[
            'body'=>'new Comment',
        ]);
        $this->assertDatabaseHas(Comment::class,$comment);
        Notification::assertSentTo($post->user,CommentNotification::class);
        Notification::assertCount(1);
    }
    /** @test */
    public function just_un_authorized_user_can_leave_a_comment()
    {
        $post=Post::factory()->create();
        $this->post('/comment/'.$post->id,[
            'body'=>'new Comment'
        ])->assertRedirect('login');
        $this->assertCount(0,Comment::all());
    }

    /** @test */
    public function Body_is_required_for_creating_a_comment()
    {
        $this->userSigneIN();
        $post=Post::factory()->create();
        $this->post('/comment/'.$post->id,[
            'body'=>''
        ])->assertSessionHasErrors('body');
    }
    /** @test */
    public function admin_can_delete_other_users_comment()
    {
        $this->userSigneIN();
        $post=Post::factory()->create();
        auth()->user()->comments()->create($comment=['body'=>'new Comment','post_id'=>$post->id]);
        $this->assertDatabaseHas(Comment::class,$comment);
        Auth::logout();
        $this->signeIn();
        $this->delete('/comment/'.Comment::first()->id);
        $this->assertDatabaseMissing(Comment::class,$comment);
    }
    /** @test */
    public function a_user_can_delete_their_comments()
    {
        $this->userSigneIN();
        $post=Post::factory()->create();
        $this->post('/comment/'.$post->id,$comment=[
            'body'=>'a comment'
        ]);
        $this->assertDatabaseHas(Comment::class,$comment);
        $this->delete('/comment/'.Comment::first()->id);
        $this->assertDatabaseMissing(Comment::class,$comment);
    }
    /** @test */
    public function after_deleting_a_comment_the_notification_get_deleted()
    {
        $this->userSigneIN();
        $post=Post::factory()->create();
        $this->post('/comment/'.$post->id,[
            'body'=>'new Comment'
        ]);
        $this->assertCount(1,Comment::all());
        $this->delete('/comment/'.($comment=Comment::first())->id);
        $this->assertCount(0,Comment::all());
        $this->assertDatabaseMissing(Comment::class,[
            'notifiable_id'=>auth()->id(),
            'notifiable_type'=>User::class,
            'data'=>json_encode(['comment_id'=>$comment->id]),
        ]);
    }
}
