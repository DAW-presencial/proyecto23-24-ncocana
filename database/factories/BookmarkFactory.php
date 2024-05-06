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
        return [
            'user_id' => User::factory()->create()->id,
            'bookmarkable_type' => $this->faker->randomElement([
                'App\Models\Book',
                'App\Models\Fanfic',
                'App\Models\Series',
                'App\Models\Movie'
            ]),
            'bookmarkable_id' => function (array $attributes) {
                switch ($attributes['bookmarkable_type']) {
                    case 'App\Models\Book':
                        return Book::factory()->create()->id;
                    case 'App\Models\Fanfic':
                        return Fanfic::factory()->create()->id;
                    case 'App\Models\Series':
                        return Series::factory()->create()->id;
                    case 'App\Models\Movie':
                        return Movie::factory()->create()->id;
                    default:
                        return null;
                }
            },
            'title' => fake()->words(3, true),
            'synopsis' => fake()->sentences(5, true),
            'notes' => fake()->sentences(5, true),
        ];
    }

    /**
     * Specify the type of bookmarkable.
     *
     * @param string $type
     * @return $this
     */
    public function ofType(string $type): self
    {
        return $this->state([
            'bookmarkable_type' => $type,
            'bookmarkable_id' => function (array $attributes) use ($type) {
                switch ($type) {
                    case 'App\Models\Book':
                        return Book::factory()->create()->id;
                    case 'App\Models\Fanfic':
                        return Fanfic::factory()->create()->id;
                    case 'App\Models\Series':
                        return Series::factory()->create()->id;
                    case 'App\Models\Movie':
                        return Movie::factory()->create()->id;
                    default:
                        return null;
                }
            }
        ]);
    }
}
