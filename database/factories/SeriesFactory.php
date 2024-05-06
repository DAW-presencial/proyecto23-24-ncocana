<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Series>
 */
class SeriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a random duration in the format HH:MM:SS
        $total_seasons = $this->faker->numberBetween(1, 20);
        $total_episodes = $this->faker->numberBetween(1, 60);

        $currentlyAt = 'Season ' . rand(1, $total_seasons) . ', episode ' . rand(1, $total_episodes);

        return [
            'actors' => fake()->name(5),
            'num_seasons' => $total_seasons,
            'num_episodes' => $total_episodes,
            'currently_at' => $currentlyAt,
        ];
    }
}
