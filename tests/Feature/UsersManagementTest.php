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
        $this->signeIn();
        $user = User::factory()->create();
        $this->get('/users')
            ->assertSee($user->id)
            ->assertSee($user->name)
            ->assertSee($user->email);
    }

    /** @test */
    public function admin_can_remove_a_user_from_the_app()
    {
        $user = User::factory()->create(['role_id'=>2]);
        $this->signeIn();
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
