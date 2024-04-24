<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>$this->faker->name,
            'body' => $this->faker->paragraph(5),
            'food' => $this->faker->name,
            'category_id'=>1,
            'touristAttraction' => $this->faker->paragraph(1),
            'user_id' => User::factory()->create()->id,
            'slug'=>$this->faker->slug,
            'city_id' => City::factory()->create(),
        ];
    }
}
