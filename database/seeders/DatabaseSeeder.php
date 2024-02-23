<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Movie;
use App\Models\Series;
use App\Models\Collection;
use App\Models\Bookmark;
// use App\Models\BookmarkCollection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BookmarkSeeder::class,
            CollectionSeeder::class,
        ]);

    }
}
