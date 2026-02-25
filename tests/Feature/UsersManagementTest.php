<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function admin_can_see_user_information_in_users_view()
    {
        $role = Role::firstOrCreate([
            'name' => Role::ROLE_USER
        ]);
        $user = User::factory()->create(['role_id'=>$role->id]);
        $this->signIn(Role::ROLE_ADMIN);
        $response=$this->get(route('users'));
            $response->assertSeeText($user->id);
            $response->assertSeeText($user->name);
            $response->assertSeeText($user->email);
    }

    /** @test */
    public function admin_can_remove_a_user_from_the_app()
    {
        $user = User::factory()->create(['role_id'=>2]);
        $this->signIn();
        $this->assertDatabaseHas(User::class,[
            'name'=>$user->name,
            'email'=>$user->email
        ]);
        $this->delete('/user/'.$user->id);
        $this->assertDatabaseMissing(User::class,[
            'name'=>$user->name,
            'email'=>$user->email
        ]);
    }
}
