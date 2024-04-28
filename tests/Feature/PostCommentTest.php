<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostCommentTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_can_leave_a_comment()
    {
        $this->userSigneIN();
        $post=Post::factory()->create();
        $this->post('/comment/'.$post->id,[
            'body'=>'hiiiiiiiiiii'
        ]);
        $this->assertCount(1,Comment::all());

    }
    /** @test */
    public function just_un_authorized_user_can_leave_a_comment()
    {
        $post=Post::factory()->create();
        $this->post('/comment/'.$post->id,[
            'body'=>'hiiiiiiiiiii'
        ])->assertRedirect('login');
        $this->assertCount(0,Comment::all());
    }

    /** @test */
    public function Body_is_required_for_creating_a_comment()
    {
        $this->userSigneIN();
        $post=Post::factory()->create();
        $response=$this->post('/comment/'.$post->id,[
            'body'=>''
        ]);
        $response->assertSessionHasErrors('body');
    }
    /** @test */
    public function a_user_can_delete_their_comments()
    {
        $this->userSigneIN();
        $post=Post::factory()->create();
        $this->post('/comment/'.$post->id,$comment=[
            'body'=>'a comment'
        ]);
        $this->assertDatabaseHas(Comment::class,$comment);
        $this->delete('/comment/'.Comment::first()->id);
        $this->assertDatabaseMissing(Comment::class,$comment);
    }

}
