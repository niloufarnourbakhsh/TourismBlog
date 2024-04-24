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
        $this->actingAs($user=$user ?? User::factory(Role::create(['name' => 'Admin']))->create());
        return $user;
    }
    public function userSigneIN($user=null)
    {
          $this->actingAs($user=$user ?? User::factory(Role::create(['name' => 'User']))->create());
          return $user;
    }
}
