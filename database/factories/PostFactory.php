<?php

namespace Database\Factories;

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
            'title'=>$this->faker->title,
            'user_id' => 1,
            'city_id' => 1,
            'body' => $this->faker->paragraph(5),
            'food' => $this->faker->name,
            'category_id'=>1,
            'touristAttraction' => $this->faker->paragraph(1),
        ];
    }
}
