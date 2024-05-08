<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    Use RefreshDatabase;
    /** @test */
    public function a_category_is_created_by_admin()
    {
        $this->signeIn();
        $this->post('/categories',[
            'name'=>'nature'
        ]);
        $this->assertDatabaseHas(Category::class,[
            'name'=>'nature'
        ]);
        $this->assertCount(1,Category::all());
    }

    /** @test */
    public function just_admin_can_create_a_category()
    {
        $this->post('/categories',[
            'name'=>'nature'
        ])->assertStatus(403);
    }

    /** @test */
    public function name_is_required_for_creating_a_new_category()
    {
        $this->signeIn();
        $this->post('/categories',[
            'name'=>''
        ])->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_category_can_be_updated()
    {
        $this->signeIn();
        $this->post('/categories',[
            'name'=>'nature'
        ]);
        $category=Category::first();
        $this->patch('/categories/'.$category->id,[
            'name'=>'nature-2'
        ]);
        $this->assertDatabaseHas(Category::class,[
            'name'=>'nature-2'
        ]);
    }
    /** @test */
    public function admin_can_delete_a_category()
    {
        $this->signeIn();
        $this->post('/categories',[
            'name'=>'nature'
        ]);
        $this->assertCount(1,Category::all());
        $category=Category::first();
        $this->delete('/categories/'.$category->id);
        $this->assertCount(0,Category::all());
    }
    /** @test */
    public function an_authenticated_user_or_other_users_can_not_delete_a_category()
    {
        $this->signeIn();
        $this->post('/categories',[
            'name'=>'nature'
        ]);
        Auth::logout();
        $category=Category::first();
        $this->delete('/categories/'.$category->id)->assertStatus(403);
    }
}
