<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a random duration in the format HH:MM:SS
        $hours = $this->faker->numberBetween(0, 2);
        $minutes = $this->faker->numberBetween(0, 59);
        $seconds = $this->faker->numberBetween(0, 59);

        $currentlyAt = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        return [
            'title' => fake()->words(3, true),
            'director' => fake()->name(),
            'actors' => fake()->name(5),
            'release_date' => fake()->date(),
            'currently_at' => $currentlyAt,
            'synopsis' => fake()->sentences(5, true),
            'notes' => fake()->sentences(5, true),
        ];
    }
}
