<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario para testing
        User::factory()
            ->create([
                'name' => 'admin',
                'email' => 'admin@mybookmarks.com',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
    }
}
