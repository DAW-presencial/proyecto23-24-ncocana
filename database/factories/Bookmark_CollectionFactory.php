<?php

namespace Database\Factories;

use App\Models\Bookmark;
use App\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Bookmark_CollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bookmark_id' => fake()->numberBetween(1, Bookmark::count()),
            'collection_id' => fake()->numberBetween(1, Collection::count()),
        ];
    }
}
