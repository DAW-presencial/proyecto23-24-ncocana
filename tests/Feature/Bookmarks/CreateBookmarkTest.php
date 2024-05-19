<?php

namespace Tests\Feature\Bookmarks;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateBookmarkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_create_bookmarks(): void
    {
        $this->postJson(route('api.v1.bookmarks.store'))
            ->assertUnauthorized();

        // $response->assertJsonApiError();
        
        $this->assertDatabaseCount('bookmarks', 0);
    }

    /** @test */
    public function can_create_book_bookmark(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->withoutExceptionHandling();

        $requestData = [
            'bookmarkable_type' => 'Book',
            'title' => 'Nuevo bookmark',
            'synopsis' => 'Esto es una sinopsis',
            'notes' => 'Esto son las notas',
            'bookmarkable' => [
                'author' => 'Noa',
                'language' => 'Spanish',
                'read_pages' => '50',
                'total_pages' => '750',
            ]
        ];
        // dump($requestData);

        $response = $this->postJson(route('api.v1.bookmarks.store'), $requestData);

        $bookmark = Bookmark::first();

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
                    'title' => $bookmark->title,
                    'synopsis' => $bookmark->synopsis,
                    'notes' => $bookmark->notes,
                    'tags' => [],
                    'bookmarkable' => [
                        'id' => $bookmark->bookmarkable->id,
                        'author' => $bookmark->bookmarkable->author,
                        'language' => $bookmark->bookmarkable->language,
                        'read_pages' => $bookmark->bookmarkable->read_pages,
                        'total_pages' => $bookmark->bookmarkable->total_pages,
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
    public function can_create_fanfic_bookmark(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->withoutExceptionHandling();

        $requestData = [
            'bookmarkable_type' => 'Fanfic',
            'title' => 'Nuevo bookmark',
            'synopsis' => 'Esto es una sinopsis',
            'notes' => 'Esto son las notas',
            'bookmarkable' => [
                'author' => 'Noa',
                'language' => 'Spanish',
                'fandom' => 'Genshin Impact',
                'relationships' => 'Tartaglia/Diluc Ragnvindr',
                'words' => '67000',
                'read_chapters' => '5',
                'total_chapters' => '60',
            ]
        ];

        $response = $this->postJson(route('api.v1.bookmarks.store'), $requestData);

        $bookmark = Bookmark::first();

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
                    'title' => $bookmark->title,
                    'synopsis' => $bookmark->synopsis,
                    'notes' => $bookmark->notes,
                    'tags' => [],
                    'bookmarkable' => [
                        'id' => $bookmark->bookmarkable->id,
                        'author' => $bookmark->bookmarkable->author,
                        'language' => $bookmark->bookmarkable->language,
                        'fandom' => $bookmark->bookmarkable->fandom,
                        'relationships' => $bookmark->bookmarkable->relationships,
                        'words' => $bookmark->bookmarkable->words,
                        'read_chapters' => $bookmark->bookmarkable->read_chapters,
                        'total_chapters' => $bookmark->bookmarkable->total_chapters,
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
    public function can_create_series_bookmark(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->withoutExceptionHandling();

        $requestData = [
            'bookmarkable_type' => 'Series',
            'title' => 'Nuevo bookmark',
            'synopsis' => 'Esto es una sinopsis',
            'notes' => 'Esto son las notas',
            'bookmarkable' => [
                'actors' => 'Actor 1, Actor 2, Actor 3',
                'num_seasons' => '6',
                'num_episodes' => '86',
                'currently_at' => 'Season 4, episode 5',
            ]
        ];

        $response = $this->postJson(route('api.v1.bookmarks.store'), $requestData);

        $bookmark = Bookmark::first();

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
                    'title' => $bookmark->title,
                    'synopsis' => $bookmark->synopsis,
                    'notes' => $bookmark->notes,
                    'tags' => [],
                    'bookmarkable' => [
                        'id' => $bookmark->bookmarkable->id,
                        'actors' => $bookmark->bookmarkable->actors,
                        'num_seasons' => $bookmark->bookmarkable->num_seasons,
                        'num_episodes' => $bookmark->bookmarkable->num_episodes,
                        'currently_at' => $bookmark->bookmarkable->currently_at,
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
    public function can_create_movie_bookmark(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->withoutExceptionHandling();

        $requestData = [
            'bookmarkable_type' => 'Movie',
            'title' => 'Nuevo bookmark',
            'synopsis' => 'Esto es una sinopsis',
            'notes' => 'Esto son las notas',
            'bookmarkable' => [
                'director' => 'Mr. Director',
                'actors' => 'Actor 1, Actor 2, Actor 3',
                'release_date' => '2024-05-10',
                'currently_at' => '1:23:52',
            ]
        ];

        $response = $this->postJson(route('api.v1.bookmarks.store'), $requestData);

        $bookmark = Bookmark::first();

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
                    'title' => $bookmark->title,
                    'synopsis' => $bookmark->synopsis,
                    'notes' => $bookmark->notes,
                    'tags' => [],
                    'bookmarkable' => [
                        'id' => $bookmark->bookmarkable->id,
                        'director' => $bookmark->bookmarkable->director,
                        'actors' => $bookmark->bookmarkable->actors,
                        'release_date' => $bookmark->bookmarkable->release_date,
                        'currently_at' => $bookmark->bookmarkable->currently_at,
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

    /** @test */
    public function can_create_tagged_bookmark(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->withoutExceptionHandling();

        $requestData = [
            'bookmarkable_type' => 'Fanfic',
            'title' => 'Nuevo bookmark',
            'synopsis' => 'Esto es una sinopsis',
            'notes' => 'Esto son las notas',
            'bookmarkable' => [
                'author' => 'Noa',
                'language' => 'Spanish',
                'fandom' => 'Genshin Impact',
                'relationships' => 'Tartaglia/Diluc Ragnvindr',
                'words' => '67000',
                'read_chapters' => '5',
                'total_chapters' => '60',
            ],
            'tags' => ['tag1', 'tag2'],
        ];

        $response = $this->postJson(route('api.v1.bookmarks.store'), $requestData);

        $bookmark = Bookmark::first();

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
                    'title' => $bookmark->title,
                    'synopsis' => $bookmark->synopsis,
                    'notes' => $bookmark->notes,
                    'tags' => $bookmark->tags->map(function ($tag) {
                        return [
                            'id' => $tag->id,
                            'name' => $tag->name,
                            'slug' => $tag->slug,
                            'pivot' => [
                                'taggable_type' => $tag->pivot->taggable_type,
                                'taggable_id' => $tag->pivot->taggable_id,
                                'tag_id' => $tag->pivot->tag_id,
                            ]
                        ];
                    })->sortBy('id')->values()->all(),
                    'bookmarkable' => [
                        'id' => $bookmark->bookmarkable->id,
                        'author' => $bookmark->bookmarkable->author,
                        'language' => $bookmark->bookmarkable->language,
                        'fandom' => $bookmark->bookmarkable->fandom,
                        'relationships' => $bookmark->bookmarkable->relationships,
                        'words' => $bookmark->bookmarkable->words,
                        'read_chapters' => $bookmark->bookmarkable->read_chapters,
                        'total_chapters' => $bookmark->bookmarkable->total_chapters,
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
        $this->assertDatabaseCount('tags', 2);
        $this->assertDatabaseCount('taggables', 2);
    }
}
