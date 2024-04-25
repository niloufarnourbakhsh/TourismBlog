<?php

namespace Tests\Unit;

use App\Events\InserPhoto;
use App\Models\Photo;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;

use Illuminate\Support\Facades\Storage;
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
}
