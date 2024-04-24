<?php


use App\Events\DeleteNotificationEvent;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Notifications\PostLikeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PostLikeTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_can_like_a_post()
    {
        $this->signeIn();
        $post=Post::factory()->create();
        $this->assertCount(1,Post::all());
        $this->post('/posts/like/'.$post->id);
        $this->assertCount(1,Like::all());
    }
    /** @test */
    public function check_the_notification_works()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $this->signeIn();
        $post=Post::factory()->create(['user_id'=>auth()->id()]);
        $this->assertCount(1,Post::all());
        $this->post('/posts/like/'.$post->id);
        $this->assertCount(1,Like::all());
        Notification::assertSentTo(auth()->user(),PostLikeNotification::class);
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
        $this->signeIn();
        $post=Post::factory()->create(['user_id'=>auth()->id()]);
        $this->post('/posts/like/'.$post->id);
        $this->assertCount(1,Like::all());
        Notification::assertSentTo(auth()->user(),PostLikeNotification::class);
        Notification::assertCount(1);
        $this->post('/posts/like/'.$post->id);
        Event::fake();
        Event::dispatch(DeleteNotificationEvent::class);
        Event::assertDispatched(DeleteNotificationEvent::class);
    }

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
