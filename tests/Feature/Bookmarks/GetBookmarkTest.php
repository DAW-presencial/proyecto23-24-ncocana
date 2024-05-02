<?php

namespace Tests\Feature\Bookmarks;

use App\Models\Bookmark;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetBookmarkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_all_bookmarks_when_no_type_is_provided()
    {
        // Arrange
        $bookmarks = Bookmark::factory(10)->create();

        // Act
        $url = route('api.v1.bookmarks.index');
        $response = $this->getJson($url);

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        // $response->assertJsonStructure([
        //     'current_page',
        //     'data' => [
        //         '*' => [
        //             'id',
        //             'user_id',
        //             'bookmarkable_type',
        //             'bookmarkable_id',
        //             'created_at',
        //             'updated_at',
        //         ]
        //     ],
        //     'first_page_url',
        //     'from',
        //     'last_page',
        //     'last_page_url',
        //     'links',
        //     'next_page_url',
        //     'path',
        //     'per_page',
        //     'prev_page_url',
        //     'to',
        //     'total',
        // ]);
    }

    // MEJORAR CREATE!!!!!!!!!
    // Create works sometimes, and sometimes not. Mejorar factory
    /** @test */
    public function can_get_filtered_bookmarks_when_type_is_provided()
    {
        // Arrange
        $movieBookmark = Bookmark::factory()->create([
            'bookmarkable_type' => Movie::class
        ]);
        $seriesBookmark = Bookmark::factory()->create([
            'bookmarkable_type' => 'App\Models\Series'
        ]);
        $bookBookmark = Bookmark::factory()->create([
            'bookmarkable_type' => 'App\Models\Book'
        ]);

        // Act
        $url = route('api.v1.bookmarks.index', [
            'filter' => [
                'bookmarkable_type' => 'Movie'
            ]
        ]);
        $response = $this->getJson($url);

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        // $response->assertJsonFragment(['id' => $movieBookmark->id]);
    }

    /** @test */
    public function can_get_filtered_and_sorted_bookmarks_when_type_is_provided()
    {
        // Arrange: Create some bookmarks with a specific bookmark type
        $bookmarks = Bookmark::factory(10)->create();

        // Act: Make a GET request to the bookmarks endpoint with filter and sorting parameters
        $url = route('api.v1.bookmarks.index', [
            'filter' => [
                'bookmarkable_type' => 'Movie'
            ],
            'sort' => 'user_id'
        ]);
        $response = $this->getJson($url);

        // Assert: Ensure the response status is 200 and contains the expected data
        $response->assertStatus(200);
        // Add additional assertions to verify the response data and sorting order if needed
    }

    /** @test */
    public function can_fetch_a_single_bookmark(): void
    {
        $this->withoutExceptionHandling();

        $bookmark = Bookmark::factory()->create();
        // dd(route('api.v1.articles.show', $article));

        // "$article->getRouteKey()" por defecto devuelve el ID
        // $response = $this->getJson('/api/v1/articles/'.$article->getRouteKey())->dump();
        $response = $this->getJson(route('api.v1.bookmarks.show', $bookmark));

        $bookmarkableArray = $bookmark->bookmarkable->toArray();

        $response->assertSee($bookmark->bookmarkable_id);
        $response->assertJson([
            'data' => [
                'type' => 'bookmarks',
                'id' => (string) $bookmark->getRouteKey(),
                'attributes' => [
                    'user_id' => $bookmark->user_id,
                    'bookmarkable_type' => $bookmark->bookmarkable_type,
                    'bookmarkable_id' => $bookmark->bookmarkable_id,
                    "bookmarkable" => $bookmarkableArray
                ],
                'links' => [
                    // 'self' => url('/api/v1/articles/'.$article->getRouteKey()),
                    'self' => url(route('api.v1.bookmarks.show', $bookmark))
                ]
            ]
        ]);
    }
}
