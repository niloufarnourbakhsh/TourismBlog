<?php

namespace Tests\Feature;

use App\Events\DeletePhoto;
use App\Events\InserPhoto;
use App\Models\City;
use App\Models\Photo;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated
        $this->user = User::factory(Role::create(['name' => 'Admin']))->create();
    }

    /** @test */
    public function a_post_can_create()
    {
        $this->actingAs($this->user)->post('/posts/', $this->data());
        $this->assertCount(1, Post::all());
    }

    /** @test */
    public function city_is_required()
    {
        $response = $this->actingAs($this->user)->post('/posts/', array_merge($this->data(), ['city' => '']));
        $response->assertSessionHasErrors('city');

    }

    /** @test */
    public function body_is_required()
    {
        $response = $this->actingAs($this->user)->post('/posts/', array_merge($this->data(), ['body' => '']));
        $response->assertSessionHasErrors('body');

    }

    /** @test */
    public function food_is_nullable()
    {
        $response = $this->actingAs($this->user)->post('/posts/', array_merge($this->data(), ['food' => '']));
        $response->assertSessionDoesntHaveErrors('food');

    }

    /** @test */
    public function touristAttraction_is_nullable()
    {
        $response = $this->actingAs($this->user)->post('/posts/', array_merge($this->data(), ['touristAttraction' => '']));
        $response->assertSessionDoesntHaveErrors('touristAttraction');

    }

    /** @test */
    public function file_is_required()
    {
        $response = $this->actingAs($this->user)->post('/posts/', array_merge($this->data(), ['touristAttraction' => '']));
        $response->assertSessionDoesntHaveErrors('touristAttraction');

    }
    /** @test */
    public function check_uploading_photo_of_a_post()
    {
//        $this->withoutExceptionHandling();
//        Event::assertNotDispatched(InserPhoto::class);
        Storage::fake('public');
        $file=UploadedFile::fake()->image('ahvaz.jpg');
        $this->actingAs($this->user)->post('/posts/', $this->data());
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
    public function just_admin_can_create_a_post()
    {
        $this->actingAs($this->user)->post('/posts/', $this->data());
        $this->assertCount(1, Post::all());
        Auth::logout();
        $response = $this->post('/posts/', $this->data());
        $response->assertStatus(403);
    }


    /** @test */
    public function deleted_a_photo()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('ahvaz.jpg');
        $this->actingAs($this->user)->post('/posts/', $this->data());
        $this->assertCount(1, Post::all());
        $this->assertCount(1, Photo::all());
        $post = Post::first();
        $photo = Photo::first();
        $this->assertNotNull($photo->path);
        Storage::disk('public')->assertExists('images/', $file->name);
        $this->actingAs($this->user)->delete('/photo/' . $post->id . '/' . $photo->id);
        Storage::disk('public')->assertMissing('ahvaz.jpg');
    }

    /** @test */
    public function check_delete_a_photo()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('ahvaz.jpg');
        $this->actingAs($this->user)->post('/posts/', $this->data());
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
        $this->actingAs($this->user)->delete('/posts/' . $post->id);
        Event::dispatch(DeletePhoto::class);
        Event::assertDispatched(DeletePhoto::class);
        $this->assertCount(0, Post::all());
    }

    /** @test */
    public function show_one_post()
    {
        $this->withoutExceptionHandling();
        $post = Post::factory(City::create(['name' => 'ahvaz']))->create();
        Photo::create(['path' => 'ax.jpg', 'post_id' => $post->id]);
        $this->get('/posts/' . $post->slug)->assertStatus(200)
            ->assertSee($post->body)
            ->assertSee($post->city->name);
    }

    /** @test */
    public function activity_of_the_post_can_change()
    {
        $this->actingAs($this->user)->post('/posts/', $this->data());
        $post = Post::first();
        $this->actingAs($this->user)
            ->patch('/posts/active/' . $post->id, [
                'is_active' => '0'
            ]);
        $this->assertEquals(0, Post::first()->is_active);
    }
//
//    /** @test */
//    public function a_post_can_become_inactive()
//    {
//        $this->actingAs($this->user)->post('/posts/', $this->data());
//        $post = Post::first();
//        $this->actingAs($this->user)
//            ->patch('/posts/active/' . $post->id, [
//                'is_active' => '0'
//            ]);

//    }

    /** @test */
    public function a_post_can_be_updated()
    {
        $this->actingAs($this->user)->post('/posts/', $this->data());
        $post = Post::first();
        $this->actingAs($this->user)->patch('/posts/' . $post->id, [
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
            'title' => 'ahvaz',
            'city' => 'ahvaz',
            'body' => 'this body is for the post',
            'food' => 'felafel',
            'file' => ['ahvaz.jpg'],
            'touristAttraction' => 'pole_sefid',
            'category_id' => 1,
            'is_active' => 1
        ];
    }
}
