<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define your tag pool
        $tags = ['fluff', 'angst', 'hurt/comfort', 'marvel', 'anime'];

        // Create bookmarks of different types
        $bookmarkTypes = ['App\Models\Movie', 'App\Models\Fanfic', 'App\Models\Book', 'App\Models\Series'];

        foreach ($bookmarkTypes as $type) {
            // Create bookmarks
            $bookmarks = Bookmark::factory(2)->ofType($type)->create();

            // Attach tags randomly to each bookmark
            foreach ($bookmarks as $bookmark) {
                // Choose a random number of tags to attach (between 1 and the total number of tags in the pool)
                $numTags = rand(1, count($tags));

                // Shuffle the tags array to randomize the selection
                shuffle($tags);

                // Select the first $numTags from the shuffled array
                $selectedTags = array_slice($tags, 0, $numTags);

                // Attach the selected tags to the bookmark
                $bookmark->attachTags($selectedTags);
            }
        }

        // Retrieve the admin user by email
        $user = User::where('email', 'admin@mybookmarks.com')->first();

        // Create bookmarks for admin user
        if ($user) {
            foreach ($bookmarkTypes as $type) {
                // Create bookmarks
                $bookmarks = Bookmark::factory(5)->ofType($type)->create([
                    'user_id' => $user->id
                ]);

                // Attach tags randomly to each bookmark
                foreach ($bookmarks as $bookmark) {
                    // Choose a random number of tags to attach (between 1 and the total number of tags in the pool)
                    $numTags = rand(1, count($tags));

                    // Shuffle the tags array to randomize the selection
                    shuffle($tags);

                    // Select the first $numTags from the shuffled array
                    $selectedTags = array_slice($tags, 0, $numTags);

                    // Attach the selected tags to the bookmark
                    $bookmark->attachTags($selectedTags);
                }
            }
        }
    }
}
