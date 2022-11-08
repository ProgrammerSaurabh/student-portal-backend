<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'role_id' => Role::inRandomOrder()->first()->id,
            'role_no' => $this->faker->word(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'location' => $this->faker->randomElement(['Delhi', 'Mumbai', 'Gurugram', 'Hyderabad', 'Bangalore', 'Chennai']),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }
}
