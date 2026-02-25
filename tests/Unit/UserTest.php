<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;
class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_role_is_sent_to_is_admin()
    {
        $adminRole = Role::create(['name' => Role::ROLE_ADMIN]);
        $user = User::factory()->create(['role_id' => $adminRole->id]);
        $this->assertTrue($user->isAdmin());
    }
    /** @test */
    public function User_role_is_sent_to_is_admin()
    {
        $user=User::factory(Role::create(['name'=>Role::ROLE_USER]))->create();
        $this->assertFalse($user->isAdmin());
    }
    /** @test */
    public function a_user_can_have_many_project()
    {
        $user=User::factory()->create();
        $this->assertInstanceOf(Collection::class,$user->posts);
    }
}
