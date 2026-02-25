<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Notifications\CommentNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function admin_can_see_all_the_notifications()
    {
        $post=Post::factory()->create(['user_id'=>$this->signIn("User")->id]);
        $comment=($user=$this->signIn("User"))->comments()->create(['body'=>'hii','post_id'=>$post->id]);
        Auth::logout();
        $notification=new CommentNotification($user,$comment,$post);
        $this->signIn()->notify($notification);
        $this->get('notifications')->assertStatus(200)
        ->assertSeeText($user->name);
    }
    /** @test */
    public function admin_can_mark_a_notifications_as_read()
    {
        $post=Post::factory()->create(['user_id'=>$this->signIn()->id]);
        $comment=($user=$this->signIn("User"))->comments()->create(['body'=>'hii','post_id'=>$post->id]);
        Auth::logout();
        $admin=$this->signIn();
        $admin->notify(new CommentNotification($user,$comment,$post));
        $notification =$admin->notifications->first();
        $this->get('/notification/markNotification/'.$notification->id);
        $this->assertNotNull($notification->fresh()->read_at);
}

}
