<?php

namespace Database\Factories;

use App\Models\Bookmark;
use App\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BookmarkCollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bookmark_id' => Bookmark::factory()->create()->id,
            'collection_id' => Collection::factory()->create()->id,
        ];
    }
}
