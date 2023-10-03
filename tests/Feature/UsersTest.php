<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;
    protected $admin;
    public $user;
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $role=Role::create(['name' => 'Admin']);
        $this->admin=User::factory()->create(['role_id'=>$role->id]);
        $this->user=User::factory()->create(['role_id'=>2]);
    }

    /** @test */
    public function user_id_is_in_view()
    {
        $this->actingAs($this->admin)->get('/users')
            ->assertSee('id',$this->user->id);
    }
    /** @test */
    public function user_name_is_in_view()
    {
        $this->actingAs($this->admin)->get('/users')
            ->assertSee($this->user->name);
    }

    /** @test */
    public function user_email_is_in_view()
    {

        $this->actingAs($this->admin)->get('/users')
            ->assertSee($this->user->email);
    }
}
