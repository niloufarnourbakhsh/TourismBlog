<?php

namespace Tests\Unit;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_has_a_path()
    {
        $post=Post::factory()->create();
        $this->assertEquals('/posts/'.$post->id, $post->path());
    }
}
