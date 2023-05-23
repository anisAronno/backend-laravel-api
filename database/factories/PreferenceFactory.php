<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Preference>
 */
class PreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sources' => $this->faker->name(),
            'authors' => $this->faker->name(),
            'categories' => $this->faker->name(),
            'user_id'           => User::all(['id'])->random(),
        ];
    }
}
