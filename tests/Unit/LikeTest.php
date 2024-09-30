<?php

namespace Tests\Unit;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function users_can_like_a_post()
    {
        $this->signeIn("User");
        $post = Post::factory()->create();
        $post->AddLike();
        $this->assertCount(1,Like::all());
    }
    /** @test */
    public function users_can_take_the_like_back()
    {
        $this->signeIn("User");
        $post = Post::factory()->create();
        $like=$post->AddLike();
        $this->assertCount(1,Like::all());
        $post->removeLike($like);
        $this->assertCount(0,Like::all());
    }
    /** @test */
    public function a_user_want_to_see_if_they_has_liked_a_post()
    {
        $this->signeIn("User");
        $post = Post::factory()->create();
        $like=$post->AddLike();
        $this->assertCount(1,Like::all());
        $this->assertTrue($post->showLikesInPost());
        $post->removeLike($like);
        $this->assertFalse($post->showLikesInPost());
    }
}
