<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Photo;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class galleryManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function only_posts_of_a_specific_category_is_shown_on_gallery()
    {
        $this->signeIn("Admin");
        $post=Post::factory(City::factory())->create();
        $photo=Photo::factory()->create(['post_id'=>$post->id]);
        $anotherPhoto=Photo::factory( $anotherPost=Post::factory(City::factory())->create())->create();
        $this->get('/gallery?category='.$post->category->name)
            ->assertSee($post->city->name)
            ->assertSee($photo->path)
            ->assertDontSee($anotherPost->city->name)
            ->assertDontSee($anotherPhoto->path);
    }
}
