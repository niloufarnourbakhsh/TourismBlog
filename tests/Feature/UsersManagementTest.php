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
        $user = User::factory()->create(['role_id'=>2]);
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
        $this->assertCount(2,User::all());
        $this->delete('/user/'.$user->id);
        $this->assertCount(1,User::all());
    }
}
