<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bookmark::factory(5)->ofType('App\Models\Movie')->create();
        Bookmark::factory(5)->ofType('App\Models\Fanfic')->create();
        Bookmark::factory(5)->ofType('App\Models\Book')->create();
        Bookmark::factory(5)->ofType('App\Models\Series')->create();
    }
}
