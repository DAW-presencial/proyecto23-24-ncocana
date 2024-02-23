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
        Collection::factory()
            ->has(Bookmark::factory()->count(1)->state(function (array $attributes, Collection $collection) {
                return ['user_id' => $collection->user_id];
            }))
            ->count(2)
            ->create();
    }
}
