<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    public function signIn($role=Role::ROLE_ADMIN, $user=null)
    {
        $role = Role::firstOrCreate([
            'name' => $role
        ]);
        $this->actingAs($user=$user ?? User::factory()->create(['role_id'=>$role->id]));
        return $user;
    }
}
