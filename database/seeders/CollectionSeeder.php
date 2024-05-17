<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define your tag pool
        $tags = ['fluff', 'angst', 'hurt/comfort', 'marvel', 'anime'];

        // Create collections for a random user
        Collection::factory()
            ->has(Bookmark::factory()->count(1))
            ->count(2)
            ->create();

        // Retrieve the admin user by email
        $user = User::where('email', 'admin@mybookmarks.com')->first();

        // Create collections for admin user
        if ($user) {
            // Create collections for a admin user
            $collections = Collection::factory()
                ->has(Bookmark::factory()->count(1))
                ->count(2)
                ->create(['user_id' => $user->id]);

            // Attach tags randomly to each collection
            foreach ($collections as $collection) {
                // Choose a random number of tags to attach (between 1 and the total number of tags in the pool)
                $numTags = rand(1, count($tags));

                // Shuffle the tags array to randomize the selection
                shuffle($tags);

                // Select the first $numTags from the shuffled array
                $selectedTags = array_slice($tags, 0, $numTags);

                // Attach the selected tags to the collection
                $collection->attachTags($selectedTags);
            }
        }
    }
}
