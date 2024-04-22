<?php

namespace Tests\Feature;

use App\Events\DeletePhoto;
use App\Events\InserPhoto;
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

class ManagePostTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function admin_can_see_the_posts_for_managing_them()
    {
        $this->signeIn();
        $post=Post::factory()->create();
        $this->get('/posts')
            ->assertStatus(200)
            ->assertSee($post->city->name);
    }
    /** @test */
    public function other_users_or_guests_can_not_see_post_management_page()
    {
        $this->get('/posts')
            ->assertStatus(403);
        $this->userSigneIN();
        $this->get('/posts')
            ->assertStatus(403);
}
    /** @test */
    public function Just_admin_see_the_create_page()
    {
        $this->signeIn();
        $this->get('posts/create')->assertStatus(200);
    }
    /** @test */
    public function other_users_or_guests_can_not_see_create_page()
    {
        $this->userSigneIN();
        $this->get('posts/create')->assertStatus(403);
        Auth::logout();
        $this->get('posts/create')->assertStatus(403);
}
    /** @test */
    public function a_post_can_be_created()
    {
        $this->signeIn();
        $this->post('/posts/', Post::factory()->create(['city_id' => ''])->toArray());
        $this->assertCount(1, Post::all());
    }

    /** @test */
    public function other_users_or_guests_can_not_create_a_post()
    {
        $this->post('/posts/', Post::factory()->create(['city_id' => ''])->toArray())->assertStatus(403);
        $this->userSigneIN();
        $this->post('/posts/', Post::factory()->create(['city_id' => ''])->toArray())->assertStatus(403);
    }
    /** @test */
    public function city_is_required()
    {
        $this->signeIn();
        $post = Post::factory()->create();
        $this->post('/posts/', array_merge($post->toArray(), ['city' => '']))
            ->assertSessionHasErrors('city');
    }
    /** @test */
    public function title_is_required()
    {
        $this->signeIn();
        $post = Post::factory()->create(['title' => ''])->toArray();
        $this->post('/posts/', $post)
            ->assertSessionHasErrors('title');
    }
    /** @test */
    public function body_is_required()
    {
        $this->signeIn();
        $post = Post::factory()->create(['body' => ''])->toArray();
        $this->post('/posts/', $post)
            ->assertSessionHasErrors('body');
    }
    /** @test */
    public function food_is_nullable()
    {
        $this->signeIn();
        $this->post('/posts/',  Post::factory()->create(['food' => ''])->toArray())
            ->assertSessionDoesntHaveErrors('food');
    }
    /** @test */
    public function touristAttraction_is_nullable()
    {
        $this->signeIn();
        $this->post('/posts/',  Post::factory()->create(['touristAttraction' => ''])->toArray())
            ->assertSessionDoesntHaveErrors('touristAttraction');
    }
    /** @test */
    public function file_is_required()
    {
        $this->signeIn();
        $post=Post::factory()->create();
        $post =array_merge( $post->toArray(),['city'=>$post->city->name]);
        $this->post('/posts/', $post)->assertSessionHasErrors('file');
    }
    /** @test */
    public function check_uploading_photo_of_a_post()
    {
        $this->signeIn();
//        $post = Post::factory()->create()->toArray();
//        Event::assertNotDispatched(InserPhoto::class);
        Storage::fake('public');
        $file = UploadedFile::fake()->image('ahvaz.jpg');
        $this->post('/posts/', $this->data());
        $this->assertCount(1, Post::all());
        $this->assertCount(1, Photo::all());
        Event::fake();
        Event::dispatch(InserPhoto::class);
        Event::assertDispatched(InserPhoto::class);
        $photo = Photo::first();
        $this->assertNotNull($photo->path);
        Storage::disk('public')->assertExists('images/', $file->name);

    }

    /** @test */
    public function deleted_a_photo()
    {
        $this->signeIn();
        Storage::fake('public');
        $file = UploadedFile::fake()->image('ahvaz.jpg');
        $this->post('/posts',$this->data());
        $this->assertCount(1, Post::all());
        $this->assertCount(1, Photo::all());
        $post = Post::first();
        $photo = Photo::first();
        $this->assertNotNull($photo->path);
        Storage::disk('public')->assertExists('images/', $file->name);
        $this->delete('/photo/' . $post->id . '/' . $photo->id);
        Storage::disk('public')->assertMissing('ahvaz.jpg');
    }

    /** @test */
    public function just_admin_can_create_a_post()
    {
        $this->signeIn();
        $this->post('/posts/', $post = Post::factory()->create()->toArray());
        $this->assertCount(1, Post::all());
        Auth::logout();
        $this->post('/posts/', $post)
            ->assertStatus(403);
    }
    /** @test */
    public function check_delete_a_photo()
    {
        $this->signeIn();
        Storage::fake('public');
        $file = UploadedFile::fake()->image('ahvaz.jpg');
        $data=array_merge($this->data());
        $this->post('/posts/',$data);
        $this->assertCount(1, Post::all());
        $this->assertCount(1, Photo::all());
        $photo = Photo::first();
        $this->assertNotNull($photo->path);
        Storage::disk('public')->assertExists('images/', $file->name);
    }
    /** @test */
    public function delete_a_post()
    {
        $post = Post::factory()->create();
        $this->assertCount(1, Post::all());
        Event::fake();
        Event::assertNotDispatched(DeletePhoto::class);
        $this->signeIn();
        $this->delete('/posts/' . $post->id);
        Event::dispatch(DeletePhoto::class);
        Event::assertDispatched(DeletePhoto::class);
        $this->assertCount(0, Post::all());
    }

    /** @test */
    public function show_one_post()
    {
        $post = Post::factory(City::create(['name' => 'ahvaz']))->create();
        Photo::create(['path' => 'ax.jpg', 'post_id' => $post->id]);
        $this->get('/posts/' . $post->slug)->assertStatus(200)
            ->assertSee($post->body)
            ->assertSee($post->city->name);
    }
    /** @test */
    public function activity_of_the_post_can_change()
    {
        $this->signeIn();
        $post = Post::factory()->create();
        $this->post('/posts/', $post->toArray());
        $post = Post::first();
        $this->patch('/posts/active/' . $post->id, [
                'is_active' => '0'
            ]);
        $this->assertEquals(0, Post::first()->is_active);
    }

    /** @test */
    public function a_post_can_be_updated()
    {
        $this->signeIn();
        $post = Post::factory()->create();
        $this->post('/posts/', $post->toArray());
        $this->patch('/posts/' . $post->id, [
            'title' => 'Tehran',
            'city' => 'Tehran',
            'cityId' => 1,
            'body' => 'this body is for editing the post',
            'food' => '',
            'file' => ['tehran.jpg'],
            'touristAttraction' => 'borje_milad',
            'category_id' => 1,
            'is_active' => 1
        ]);
        $this->assertEquals('Tehran', Post::first()->title);
        $this->assertEquals('Tehran', Post::first()->city->name);
        $this->assertEquals('this body is for editing the post', Post::first()->body);
        $this->assertEquals('', Post::first()->food);
        $this->assertEquals('borje_milad', Post::first()->touristAttraction);
    }
    /**
     * @return array
     */
    protected function data(): array
    {
        return [
            'title' => $this->faker->name,
            'city' => $this->faker->city,
            'body' => $this->faker->paragraph(3),
            'food' => $this->faker->name,
            'file' => ['ahvaz.jpg'],
            'touristAttraction' => $this->faker->paragraph,
            'category_id' => 1,
            'is_active' => 1
        ];
    }
}
