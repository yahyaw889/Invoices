<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('ar_SA'); // You can use 'ar_EG', 'ar_LB', etc.

        return [
            'name' => $faker->word, // Generates an Arabic word
            'description' => $faker->sentence, // Generates an Arabic sentence
            'section_id' => $faker->randomElement([1, 2, 3, 4, 5]), // Random section ID
        ];
    }

}
