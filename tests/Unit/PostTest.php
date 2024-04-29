<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_has_a_path()
    {
        $post = Post::factory()->create();
        $this->assertEquals('/posts/' . $post->id, $post->path());
    }
    /** @test */
    public function a_post_belongs_to_a_user()
    {
        $post=Post::factory()->create();
        $this->assertInstanceOf(User::class,$post->user);
    }
}
