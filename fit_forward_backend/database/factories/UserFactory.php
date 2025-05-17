<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $height = $this->faker->numberBetween(150, 200);
        $weight = $this->faker->numberBetween(45, 120);
        $heightInMeters = $height / 100;
        $bmi = round($weight / ($heightInMeters * $heightInMeters), 2);

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            // Remove email_verified_at
            'password' => Hash::make('password'),
            'age' => $this->faker->numberBetween(18, 65),
            'gender' => $this->faker->numberBetween(1, 2), // 1 for male, 2 for female
            'height' => $height,
            'weight' => $weight,
            'bmi' => $bmi,
            'activity_level' => $this->faker->numberBetween(1, 5),
        ];
    }
}
