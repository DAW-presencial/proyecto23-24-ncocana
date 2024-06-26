<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total_pages = rand(1, 1000);

        return [
            'author' => fake()->name(),
            'language' => fake()->languageCode(),
            'read_pages' => rand(1, $total_pages),
            'total_pages' => $total_pages,
        ];
    }
}
