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

        $response = $this->postJson(route('api.v1.bookmarks.store'), [
            $requestData
        ]);

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
}
