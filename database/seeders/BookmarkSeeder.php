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
        Bookmark::factory(5)->ofType('App\Models\Movie')->create();
        Bookmark::factory(5)->ofType('App\Models\Fanfic')->create();
        Bookmark::factory(5)->ofType('App\Models\Book')->create();
        Bookmark::factory(5)->ofType('App\Models\Series')->create();
        
        // Retrieve the admin user by email
        $user = User::where('email', 'admin@mybookmarks.com')->first();

        // Create bookmarks for admin user
        if ($user) {
            Bookmark::factory(5)->ofType('App\Models\Movie')->create([
                'user_id' => $user->id
            ]);
            Bookmark::factory(5)->ofType('App\Models\Fanfic')->create([
                'user_id' => $user->id
            ]);
            Bookmark::factory(5)->ofType('App\Models\Book')->create([
                'user_id' => $user->id
            ]);
            Bookmark::factory(5)->ofType('App\Models\Series')->create([
                'user_id' => $user->id
            ]);
        }
    }
}
