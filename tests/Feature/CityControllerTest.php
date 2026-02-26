<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_see_all_the_cities()
    {
        $this->signIn();
        $city=City::factory()->create();
        $this->get(route('cities.index'))
        ->assertSeeText($city->id);
    }

    public function test_just_admin_can_see_city_management_page()
    {
        $city=City::factory()->create();
        $this->get(route('cities.index'))
            ->assertForbidden();
        $this->signIn(Role::ROLE_USER);
        $this->get(route('cities.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_the_city()
    {
        $this->signIn();
        $this->post(route('cities.store'),$city=[
            'name'=>'ahvaz'
        ]);
        $this->assertDatabaseHas(City::class,$city);
    }

    public function test_just_admin_can_create_a_city()
    {
        $city=City::factory()->create();
        $this->post(route('cities.store'))
            ->assertForbidden();
        $this->signIn(Role::ROLE_USER);
        $this->post(route('cities.store'))
            ->assertForbidden();
    }

    public function test_name_is_required_to_create_a_city()
    {
        $this->signIn();
        $this->post(route('cities.store'),$city=[
            'name'=>''
        ])->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_the_city_name()
    {
        $city=City::factory()->create();
        $this->signIn();
        $this->put(route('cities.update',$city),$city=[
            'name'=>'shiraz'
        ]);
        $this->assertDatabaseHas(City::class,$city);
    }
    public function test_just_admin_can_update_the_city_name()
    {
        $city=City::factory()->create();
        $this->put(route('cities.update',$city),$city=[
            'name'=>'shiraz'
        ])->assertForbidden();
        $this->signIn(Role::ROLE_USER);
        $city=City::factory()->create();
        $this->put(route('cities.update',$city),$city=[
            'name'=>'shiraz'
        ])->assertForbidden();
    }

    public function test_admin_can_delete_a_city()
    {
        $this->signIn();
        $city=City::factory()->create();
        $this->delete(route('cities.destroy',$city));
        $this->assertDatabaseMissing(City::class,$city->toArray());
    }

    public function test_just_admin_can_delete_a_city()
    {
        $city=City::factory()->create();
        $this->delete(route('cities.destroy',$city))->assertForbidden();
        $this->signIn(Role::ROLE_USER);
        $this->delete(route('cities.destroy',$city))->assertForbidden();
    }
}
