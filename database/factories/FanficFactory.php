<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookmark>
 */
class FanficFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total_chapters = rand(1, 100);

        return [
            'author' => fake()->name(),
            'fandom' => fake()->words(2, true),
            'relationships' => fake()->name() . "/" . fake()->name(),
            'language' => fake()->languageCode(),
            'words' => rand(1, 200000),
            'read_chapters' => rand(1, $total_chapters),
            'total_chapters' => $total_chapters,
        ];
    }
}
