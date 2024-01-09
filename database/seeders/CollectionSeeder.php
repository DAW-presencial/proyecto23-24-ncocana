<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Collection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Collection::factory(5)->create();

        // Creates 2 bookmarks per collection created.
        Collection::factory()
            ->has(Bookmark::factory()->count(2)->state(function (array $attributes, Collection $collection) {
                return ['user_id' => $collection->user_id];
            }))
            ->count(2)
            ->create(['user_id' => 3]);
    }
}
