<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signeIn($role="Admin", $user=null)
    {
        $role=Role::create(['name' => $role]);
        $this->actingAs($user=$user ?? User::factory()->create(['role_id'=>$role->id]));
        return $user;
    }
}
