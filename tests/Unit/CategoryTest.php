<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;
class CategoryTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_post_belongs_to_a_category()
    {
        $post=Post::factory(Category::factory())->create();
        $this->assertInstanceOf(Category::class,$post->category);
    }
}
