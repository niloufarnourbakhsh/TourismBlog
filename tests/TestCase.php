<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signeIn($user=null)
    {
        $role=Role::create(['name' => 'Admin']);
        $this->actingAs($user=$user ?? User::factory()->create(['role_id'=>$role->id]));
        return $user;
    }
    public function userSigneIN($user=null)
    {
        $role=Role::create(['name' => 'User']);
        $this->actingAs($user=$user ?? User::factory()->create(['role_id'=>$role->id]));
          return $user;
    }
}
