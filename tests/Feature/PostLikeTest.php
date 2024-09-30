<?php


use App\Events\DeleteNotificationEvent;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
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
        $post=Post::factory()->create();
        $this->signeIn();
        $this->post('/posts/like/'.$post->id);
        $this->assertCount(1,Like::all());
    }
    /** @test */
    public function unauthorized_user_can_not_like_a_post()
    {
        $post=Post::factory()->create();
        $this->post('/posts/like/'.$post->id)->assertRedirect('login');
    }
//    /** @test */
//    public function check_the_notification_sent_when_a_post_is_liked()
//    {
//        Notification::fake();
//        Notification::assertNothingSent();
//        $this->userSigneIN();
//        $post=Post::factory()->create(['user_id'=>auth()->id()]);
//        $this->post('/posts/like/'.$post->id);
//        $this->assertCount(1,Like::all());
//        Notification::assertSentTo(auth()->user(),PostLikeNotification::class);
//        Notification::assertCount(1);
//    }
    /** @test */
    public function check_the_notification_sent_when_a_post_is_liked()
    {
        $adminUser = User::factory()->create([
            'role_id' => (Role::firstOrCreate(['name' => 'Admin']))->id,
        ]);
        $post = Post::factory()->create([
            'user_id' => $adminUser->id,
        ]);
        Notification::fake();
        Notification::assertNothingSent();

        $this->signeIn("User");

        $this->post('/posts/like/' . $post->id);
        $this->assertCount(1, Like::all());
        Notification::assertSentTo($adminUser, PostLikeNotification::class);
        Notification::assertCount(1);
    }

    /** @test */
    public function a_user_can_take_the_like_back()
    {
        $adminUser = User::factory()->create([
            'role_id' => (Role::firstOrCreate(['name' => 'Admin']))->id,
        ]);
        $post = Post::factory()->create([
            'user_id' => $adminUser->id,
        ]);
        Notification::fake();
        Notification::assertNothingSent();
        $this->signeIn("User");
        $post->likePost();
        $this->assertCount(1,Like::all());
        Notification::assertSentTo($adminUser,PostLikeNotification::class);
        Notification::assertCount(1);
        $this->post('/posts/like/'.$post->id);
        Event::fake();
        Event::dispatch(DeleteNotificationEvent::class);
        Event::assertDispatched(DeleteNotificationEvent::class);
        $this->assertCount(0,Like::all());
    }

    /** @test */
    public function a_notification_is_deleted_after_taking_back_the_like ()
    {
        $adminUser = User::factory()->create([
            'role_id' => (Role::firstOrCreate(['name' => 'Admin']))->id,
        ]);
        $post = Post::factory()->create([
            'user_id' => $adminUser->id,
        ]);
        $this->signeIn("User");
        $like=$post->likePost();
        $this->assertCount(1,Like::all());
        $this->post('/posts/like/'.$post->id);
        $this->assertCount(0,Like::all());
        $this->assertDatabaseMissing(Like::class,[
            'notifiable_id'=>auth()->id(),
            'notifiable_type'=>\App\Models\User::class,
            'data'=>json_encode(['like_id'=>$like->id]),
        ]);
    }

}
