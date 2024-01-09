<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookmark>
 */
class BookmarkFactory extends Factory
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
            'user_id' => fake()->numberBetween(1, User::count()),
            'title' => fake()->words(3, true),
            'author' => fake()->name(),
            'fandom' => fake()->words(2, true),
            'relationships' => fake()->name() . "/" . fake()->name(),
            // 'tags' => fake()->randomElements(['angst', 'fluff', 'happy ending', 'hurt/comfort'], 2),
            'language' => fake()->languageCode(),
            'words' => rand(1, 100000),
            'chapters_read' => rand(1, $total_chapters),
            'total_chapters' => $total_chapters,
            'synopsis' => fake()->sentences(5, true),
        ];
    }
}
