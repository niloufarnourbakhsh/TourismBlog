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
      return  $this->actingAs($user ?? User::factory(Role::create(['name' => 'Admin']))->create());
    }
}
