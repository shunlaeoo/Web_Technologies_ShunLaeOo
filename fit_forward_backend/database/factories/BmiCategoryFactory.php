<?php

namespace Database\Factories;

use App\Models\BmiCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BmiCategory>
 */
class BmiCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BmiCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a random BMI range
        $min = $this->faker->randomFloat(1, 15, 35);
        $max = $min + $this->faker->randomFloat(1, 3, 10);

        // BMI category names
        $categories = ['Underweight', 'Normal', 'Overweight', 'Obese', 'Severely Obese'];

        return [
            'name' => $this->faker->randomElement($categories),
            'min' => $min,
            'max' => $max,
        ];
    }

    /**
     * Define a state for underweight BMI category.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function underweight()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Underweight',
                'min' => 0,
                'max' => 18.4,
            ];
        });
    }

    /**
     * Define a state for normal BMI category.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function normal()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Normal',
                'min' => 18.5,
                'max' => 24.9,
            ];
        });
    }

    /**
     * Define a state for overweight BMI category.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function overweight()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Overweight',
                'min' => 25.0,
                'max' => 29.9,
            ];
        });
    }

    /**
     * Define a state for obese BMI category.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function obese()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Obese',
                'min' => 30.0,
                'max' => 34.9,
            ];
        });
    }
}