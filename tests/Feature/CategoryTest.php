<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    Use RefreshDatabase;
    private $admin;
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $role=Role::create(['name'=>'Admin']);
        $this->admin=User::factory()->create(['role_id'=>$role->id]);
    }
    /** @test */
    public function create_a_new_category()
    {
        $this->actingAs($this->admin)->post('/categories',[
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
        $response=$this->actingAs($this->admin)->post('/categories',[
            'name'=>''
        ]);
        $response->assertSessionHasErrors('name');

    }

    /** @test */
    public function a_category_edition()
    {
        $this->actingAs($this->admin)->post('/categories',[
            'name'=>'nature'
        ]);
        $this->assertCount(1,Category::all());
        $category=Category::first();
        $this->actingAs($this->admin)->patch('/categories/'.$category->id,[
            'name'=>'nature-2'
        ]);
        $this->assertEquals('nature-2',Category::first()->name);
    }
    /** @test */
    public function admin_can_delete_a_collection()
    {
        $this->actingAs($this->admin)->post('/categories',[
            'name'=>'nature'
        ]);
        $this->assertCount(1,Category::all());
        $category=Category::first();
        $this->actingAs($this->admin)->delete('/categories/'.$category->id);
        $this->assertCount(0,Category::all());
    }
}
