<?php

namespace Database\Factories;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exercise>
 */
class ExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $exerciseNames = [
            'Push-ups', 'Squats', 'Lunges', 'Plank', 'Burpees',
            'Mountain Climbers', 'Jumping Jacks', 'Deadlifts',
            'Bench Press', 'Pull-ups', 'Sit-ups', 'Crunches'
        ];

        return [
            'name' => $this->faker->randomElement($exerciseNames),
            'instructions' => $this->faker->paragraph(),
            'video_url' => 'https://www.youtube.com/watch?v=' . $this->faker->regexify('[A-Za-z0-9_-]{11}'),
            'image' => 'exercises/' . $this->faker->word() . '.jpg',
        ];
    }
}