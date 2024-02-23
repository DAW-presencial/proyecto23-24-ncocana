<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Fanfic;
use App\Models\Movie;
use App\Models\Series;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookmark>
 */
class BookmarkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bookmarkableType = fake()->randomElement([
            'App\Models\Book',
            'App\Models\Fanfic',
            'App\Models\Series',
            'App\Models\Movie'
        ]);

        $bookmarkable = null;

        switch ($bookmarkableType) {
            case 'App\Models\Book':
                $bookmarkable = Book::factory()->create();
                break;
            case 'App\Models\Fanfic':
                $bookmarkable = Fanfic::factory()->create();
                break;
            case 'App\Models\Series':
                $bookmarkable = Series::factory()->create();
                break;
            case 'App\Models\Movie':
                $bookmarkable = Movie::factory()->create();
                break;
        }

        return [
            'user_id' => User::factory()->create()->id,
            'bookmarkable_id' => $bookmarkable->id,
            'bookmarkable_type' => $bookmarkableType,
            // You can add more fields as per your requirement
        ];
    }
}
