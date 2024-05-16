<?php

namespace Tests\Feature\Bookmarks;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateBookmarkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_update_bookmarks(): void
    {
        $bookmark = Bookmark::factory()->create();

        $this->patchJson(route('api.v1.bookmarks.update', $bookmark))
            ->assertUnauthorized();
    }
    
    /** @test */
    public function can_update_book_bookmarks(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $bookmark = Bookmark::factory()->ofType('App\Models\Book')->create();
        
        $requestData = [
            'bookmarkable_type' => 'Book',
            'title' => 'Updated bookmark',
            'synopsis' => 'Sinopsis actualizada',
            // 'notes' => 'Notas actualizada',
            'bookmarkable' => [
                'author' => 'Noa',
                // 'language' => 'Spanish',
                'read_pages' => '50',
                'total_pages' => '750',
            ]
        ];

        $response = $this->patchJson(route('api.v1.bookmarks.update', $bookmark), $requestData)->assertOk();

        $response->assertHeader(
            'Location',
            route('api.v1.bookmarks.show', $bookmark)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'bookmarks',
                'id' => (string) $bookmark->getRouteKey(),
                'attributes' => [
                    'user_id' => $bookmark->user_id,
                    'bookmarkable_type' => $bookmark->bookmarkable_type,
                    'bookmarkable_id' => $bookmark->bookmarkable_id,
                    'title' => $requestData['title'],
                    'synopsis' => $requestData['synopsis'],
                    'notes' => $bookmark->notes,
                    'tags' => [],
                    'bookmarkable' => [
                        'id' => $bookmark->bookmarkable->id,
                        'author' => $requestData['bookmarkable']['author'],
                        'language' => $bookmark->bookmarkable->language,
                        'read_pages' => (int) $requestData['bookmarkable']['read_pages'],
                        'total_pages' => (int) $requestData['bookmarkable']['total_pages'],
                    ]
                ],
                'links' => [
                    'self' => route('api.v1.bookmarks.show', $bookmark)
                ]
            ],
        ]);

        $this->assertDatabaseCount('bookmarks', 1);
        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseCount('fanfics', 0);
        $this->assertDatabaseCount('series', 0);
        $this->assertDatabaseCount('movies', 0);
    }
    
    /** @test */
    public function can_update_fanfic_bookmarks(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $bookmark = Bookmark::factory()->ofType('App\Models\Fanfic')->create();
        
        $requestData = [
            'bookmarkable_type' => 'Fanfic',
            'title' => 'Updated bookmark',
            'synopsis' => 'Sinopsis actualizada',
            // 'notes' => 'Notas actualizada',
            'bookmarkable' => [
                'author' => 'Noa',
                // 'language' => 'Spanish',
                'fandom' => 'Genshin Impact',
                'relationships' => 'Tartaglia/Diluc Ragnvindr',
                'words' => '67000',
                'read_chapters' => '5',
                'total_chapters' => '60',
            ]
        ];

        $response = $this->patchJson(route('api.v1.bookmarks.update', $bookmark), $requestData)->assertOk();

        $response->assertHeader(
            'Location',
            route('api.v1.bookmarks.show', $bookmark)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'bookmarks',
                'id' => (string) $bookmark->getRouteKey(),
                'attributes' => [
                    'user_id' => $bookmark->user_id,
                    'bookmarkable_type' => $bookmark->bookmarkable_type,
                    'bookmarkable_id' => $bookmark->bookmarkable_id,
                    'title' => $requestData['title'],
                    'synopsis' => $requestData['synopsis'],
                    'notes' => $bookmark->notes,
                    'tags' => [],
                    'bookmarkable' => [
                        'id' => $bookmark->bookmarkable->id,
                        'author' => $requestData['bookmarkable']['author'],
                        'language' => $bookmark->bookmarkable->language,
                        'fandom' => $requestData['bookmarkable']['fandom'],
                        'relationships' => $requestData['bookmarkable']['relationships'],
                        'words' => (int) $requestData['bookmarkable']['words'],
                        'read_chapters' => (int) $requestData['bookmarkable']['read_chapters'],
                        'total_chapters' => (int) $requestData['bookmarkable']['total_chapters'],
                    ]
                ],
                'links' => [
                    'self' => route('api.v1.bookmarks.show', $bookmark)
                ]
            ],
        ]);

        $this->assertDatabaseCount('bookmarks', 1);
        $this->assertDatabaseCount('books', 0);
        $this->assertDatabaseCount('fanfics', 1);
        $this->assertDatabaseCount('series', 0);
        $this->assertDatabaseCount('movies', 0);
    }
    
    /** @test */
    public function can_update_series_bookmarks(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $bookmark = Bookmark::factory()->ofType('App\Models\Series')->create();
        
        $requestData = [
            'bookmarkable_type' => 'Series',
            'title' => 'Updated bookmark',
            'synopsis' => 'Sinopsis actualizada',
            // 'notes' => 'Notas actualizada',
            'bookmarkable' => [
                // 'actors' => 'Actor 1, Actor 2, Actor 3',
                'num_seasons' => '6',
                'num_episodes' => '86',
                'currently_at' => 'Season 4, episode 5',
            ]
        ];

        $response = $this->patchJson(route('api.v1.bookmarks.update', $bookmark), $requestData)->assertOk();

        $response->assertHeader(
            'Location',
            route('api.v1.bookmarks.show', $bookmark)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'bookmarks',
                'id' => (string) $bookmark->getRouteKey(),
                'attributes' => [
                    'user_id' => $bookmark->user_id,
                    'bookmarkable_type' => $bookmark->bookmarkable_type,
                    'bookmarkable_id' => $bookmark->bookmarkable_id,
                    'title' => $requestData['title'],
                    'synopsis' => $requestData['synopsis'],
                    'notes' => $bookmark->notes,
                    'tags' => [],
                    'bookmarkable' => [
                        'id' => $bookmark->bookmarkable->id,
                        'actors' => $bookmark->bookmarkable->actors,
                        'num_seasons' => (int) $requestData['bookmarkable']['num_seasons'],
                        'num_episodes' => (int) $requestData['bookmarkable']['num_episodes'],
                        'currently_at' => $requestData['bookmarkable']['currently_at'],
                    ]
                ],
                'links' => [
                    'self' => route('api.v1.bookmarks.show', $bookmark)
                ]
            ],
        ]);

        $this->assertDatabaseCount('bookmarks', 1);
        $this->assertDatabaseCount('books', 0);
        $this->assertDatabaseCount('fanfics', 0);
        $this->assertDatabaseCount('series', 1);
        $this->assertDatabaseCount('movies', 0);
    }
    
    /** @test */
    public function can_update_movie_bookmarks(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $bookmark = Bookmark::factory()->ofType('App\Models\Movie')->create();
        
        $requestData = [
            'bookmarkable_type' => 'Movie',
            'title' => 'Updated bookmark',
            'synopsis' => 'Sinopsis actualizada',
            // 'notes' => 'Notas actualizada',
            'bookmarkable' => [
                // 'director' => 'Mr. Director',
                'actors' => 'Actor 1, Actor 2, Actor 3',
                'release_date' => '2024-05-10',
                'currently_at' => '1:23:52',
            ]
        ];

        $response = $this->patchJson(route('api.v1.bookmarks.update', $bookmark), $requestData)->assertOk();

        $response->assertHeader(
            'Location',
            route('api.v1.bookmarks.show', $bookmark)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'bookmarks',
                'id' => (string) $bookmark->getRouteKey(),
                'attributes' => [
                    'user_id' => $bookmark->user_id,
                    'bookmarkable_type' => $bookmark->bookmarkable_type,
                    'bookmarkable_id' => $bookmark->bookmarkable_id,
                    'title' => $requestData['title'],
                    'synopsis' => $requestData['synopsis'],
                    'notes' => $bookmark->notes,
                    'tags' => [],
                    'bookmarkable' => [
                        'id' => $bookmark->bookmarkable->id,
                        'director' => $bookmark->bookmarkable->director,
                        'actors' => $requestData['bookmarkable']['actors'],
                        'release_date' => $requestData['bookmarkable']['release_date'] . 'T00:00:00.000000Z',
                        'currently_at' => $requestData['bookmarkable']['currently_at'],
                    ]
                ],
                'links' => [
                    'self' => route('api.v1.bookmarks.show', $bookmark)
                ]
            ],
        ]);

        $this->assertDatabaseCount('bookmarks', 1);
        $this->assertDatabaseCount('books', 0);
        $this->assertDatabaseCount('fanfics', 0);
        $this->assertDatabaseCount('series', 0);
        $this->assertDatabaseCount('movies', 1);
    }
}
