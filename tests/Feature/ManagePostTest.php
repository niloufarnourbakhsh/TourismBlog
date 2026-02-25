<?php

namespace Tests\Feature;

use App\Events\DeletePhoto;
use App\Events\InsertPhoto;
use App\Models\City;
use App\Models\Photo;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Throwable;

class ManagePostTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function admin_can_see_the_posts_for_managing_them()
    {
        $this->signIn();
        $post = Post::factory()->create();
        $this->get('/posts')
            ->assertStatus(200)
            ->assertSee($post->city->name);
    }
    /** @test */
    public function other_users_or_guests_can_not_see_post_management_page()
    {
        $this->get('/posts')
            ->assertStatus(403);
        $this->signIn("User");
        $this->get('/posts')
            ->assertStatus(403);
    }

    /** @test */
    public function Just_admin_see_the_create_page()
    {
        $this->signIn();
        $this->get('posts/create')->assertStatus(200);
    }

    /** @test */
    public function other_users_or_guests_can_not_see_create_page()
    {
        $this->signIn("User");
        $this->get('posts/create')->assertStatus(403);
        Auth::logout();
        $this->get('posts/create')->assertStatus(403);
    }

    /** @test */
    public function a_post_can_be_created()
    {
        $this->signIn();
        $this->post('/posts/', Post::factory()->create()->toArray());
        $this->assertCount(1, Post::all());
    }
    /** @test */
    public function an_authenticated_user_or_other_users_can_not_store_a_post()
    {
        $this->post('/posts/', Post::factory()->raw())->assertStatus(403);
        $this->signIn("User");
        $this->post('/posts/', Post::factory()->raw())->assertStatus(403);
    }
    /** @test */
    public function a_post_requires_a_city()
    {
        $this->signIn();
        $this->post('/posts/', array_merge( Post::factory()->create()->toArray(), ['city' => '']))
            ->assertSessionHasErrors('city');
    }
    /** @test */
    public function a_post_requires_a_title()
    {
        $this->signIn();
        $this->post('/posts/', Post::factory()->create(['title' => ''])->getRawOriginal())
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_post_requires_a_body()
    {
        $this->signIn();
        $this->post('/posts/', Post::factory()->create(['body' => ''])->getRawOriginal())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function food_is_nullable_in_the_post()
    {
        $this->signIn();
        $this->post('/posts/', Post::factory()->create(['food' => ''])->toArray())
            ->assertSessionDoesntHaveErrors('food');
    }
    /** @test */
    public function touristAttraction_is_nullable_in_the_post()
    {
        $this->signIn();
        $this->post('/posts/', Post::factory()->create(['touristAttraction' => ''])->toArray())
            ->assertSessionDoesntHaveErrors('touristAttraction');
    }

    /** @test */
    public function a_post_must_have_at_least_one_photo()
    {
        $this->signIn();
        $post = array_merge(($post= Post::factory()->create())->toArray(), ['city' => $post->city->name]);
        $this->post('/posts/', $post)->assertSessionHasErrors('file');
    }
    /** @test */
    public function the_event_must_dispatch_after_data_insertion()
    {
        $this->signIn();
        $this->post('/posts/', array_merge(Post::factory()->create()->toArray(),[
            'file'=>['ahvaz.jpg'],
            'city'=>$this->faker->city
        ]));
        Event::fake();
        Event::assertNotDispatched(InsertPhoto::class);
        Event::dispatch(InsertPhoto::class);
        Event::assertDispatched(InsertPhoto::class);
    }
    /** @test */
    public function a_photo_of_the_post_can_uploaded()
    {
        $this->signIn();
        Storage::fake('public');
        $file = UploadedFile::fake()->image('ahvaz.jpg');
        $this->post('/posts', array_merge(Post::factory()->create()->toArray(),[
            'file'=>[$file->name],
            'city'=>$this->faker->city
            ]));
        $photo = Photo::first();
        $this->assertNotNull($photo->path);
        Storage::disk('public')->assertExists('images/', $file->name);
    }
    /** @test */
    public function admin_can_update_the_post()
    {
        $this->signIn();
        $this->post('/posts/',($post=Post::factory()->create())->toArray());
        $this->patch($post->path(), $attributes=[
            'title' => 'Tehran',
            'city_id' => 1,
            'body' => 'this body is for editing the post',
            'food' => null,
            'touristAttraction' => 'borje_milad',
            'category_id' => 1,
            'is_active' => 1
        ]);
        $this->assertDatabaseHas(Post::class,$attributes);
    }
    /** @test */
    public function an_authenticated_user_or_other_users_can_not_update_a_post()
    {
        $this->post('/posts/',($post=Post::factory()->create())->toArray());
        $this->patch($post->path(), $attributes=[
            'title' => 'Tehran',
            'city_id' => 1,
            'body' => 'this body is for editing the post',
            'food' => null,
            'touristAttraction' => 'borje_milad',
            'category_id' => 1,
            'is_active' => 1
        ])->assertStatus(403);
}

    /** @test */
    public function check_if_the_photo_that_uploaded_can_be_removed()
    {
        $this->signIn();
        Storage::fake('public');
        $file = UploadedFile::fake()->image('ahvaz.jpg');
        $this->post('/posts', array_merge(($post=Post::factory()->create())->toArray(),[
            'file'=>[$file->name],
            'city'=>$this->faker->city
        ]));
        $photo = Photo::first();
        $this->assertNotNull($photo->path);
        Storage::disk('public')->assertExists('images/', $file->name);
        $this->delete('/photo/' . $post->id . '/' . $photo->id);
        Storage::disk('public')->assertMissing('ahvaz.jpg');
    }
    /** @test */
    public function admin_can_delete_a_post()
    {
        $post = Post::factory()->create();
        $this->assertCount(1, Post::all());
        $this->assertCount(1, City::all());
        Event::fake();
        Event::assertNotDispatched(DeletePhoto::class);
        $this->signIn();
        $this->delete('/posts/' . $post->id);
        Event::dispatch(DeletePhoto::class);
        Event::assertDispatched(DeletePhoto::class);
        $this->assertDatabaseMissing(Post::class,$post->toArray());
        $this->assertCount(0, City::all());
    }
    /** @test */
    public function an_authenticated_user_or_other_users_can_not_delete_a_post()
    {
        $post = Post::factory()->create();
        $this->delete('/posts/' . $post->id)->assertStatus(403);
        $this->signIn("User");
        $this->delete('/posts/' . $post->id)->assertStatus(403);
    }

    /** @test */
    public function show_a_post_to_everyone()
    {
        $post = Post::factory(City::factory())->create();
        Photo::create(['path' => 'axe.jpg', 'post_id' => $post->id]);
        $this->get('/posts/' . $post->slug)->assertStatus(200)
            ->assertSee($post->body)
            ->assertSee($post->city->name);
    }
    /** @test */
    public function activity_of_the_post_can_change()
    {
        $this->signIn();
        $post=Post::factory()->create();
        $this->patch('/posts/active/' . $post->id, [
            'is_active' => false
        ]);
        $this->assertFalse( Post::first()->is_active);
        $this->patch('/posts/active/' . $post->id, [
            'is_active' => true
        ]);
        $this->assertTrue( Post::first()->is_active);
    }
    /** @test */
    public function only_active_post_is_shown_on_the_gallery()
    {
        $this->signIn();
        $post=Post::factory()->create();
        $this->get('/gallery')->assertSee($post->city->name)
            ->assertSee($post->photo);
        $post=Post::first();
        $this->patch('/posts/active/' . $post->id, [
            'is_active' => false
        ]);
        $this->get('/gallery')->assertDontSee($post->city->name)
        ->assertDontSee($post->photo);
    }
}
