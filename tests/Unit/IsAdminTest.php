<?php

namespace Tests\Unit;

use App\Http\Middleware\IsAdmin;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    /** @test */
    public function admin_role_is_sent_to_is_admin()
    {
        $user=User::factory(Role::create(['name'=>'Admin']))->create();
        $this->assertTrue($user->IsAdmin());
    }

    /** @test */
    public function User_role_is_sent_to_is_admin()
    {
        $this->withoutExceptionHandling();
        $user=User::factory(Role::create(['name'=>'User']))->create();
        $this->assertFalse($user->IsAdmin());
    }
}
