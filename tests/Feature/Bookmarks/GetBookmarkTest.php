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
    public function can_fetch_a_single_bookmark(): void
    {
        $this->withoutExceptionHandling();

        $bookmark = Bookmark::factory()->create();

        $response = $this->getJson(route('api.v1.bookmarks.show', $bookmark));

        $bookmarkableArray = $bookmark->bookmarkable->toArray();

        $response->assertSee($bookmark->bookmarkable_id);
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
                    "bookmarkable" => $bookmarkableArray
                ],
                'links' => [
                    'self' => url(route('api.v1.bookmarks.show', $bookmark))
                ]
            ]
        ]);
    }

    /** @test */
    public function can_fetch_all_bookmarks()
    {
        $this->withoutExceptionHandling();

        $bookmarks = Bookmark::factory()->count(3)->create();

        $response = $this->getJson(route('api.v1.bookmarks.index'));
        
        $bookmarkableArray0 = $bookmarks[0]->bookmarkable->toArray();
        $bookmarkableArray1 = $bookmarks[1]->bookmarkable->toArray();
        $bookmarkableArray2 = $bookmarks[2]->bookmarkable->toArray();

        $response->assertJson([
            'data' => [
                [
                    'type' => 'bookmarks',
                    'id' => (string) $bookmarks[0]->getRouteKey(),
                    'attributes' => [
                        'user_id' => $bookmarks[0]->user_id,
                        'bookmarkable_type' => $bookmarks[0]->bookmarkable_type,
                        'bookmarkable_id' => $bookmarks[0]->bookmarkable_id,
                        'title' => $bookmarks[0]->title,
                        'synopsis' => $bookmarks[0]->synopsis,
                        'notes' => $bookmarks[0]->notes,
                        "bookmarkable" => $bookmarkableArray0
                    ],
                    'links' => [
                        'self' => route('api.v1.bookmarks.show', $bookmarks[0])
                    ]
                ],
                [
                    'type' => 'bookmarks',
                    'id' => (string) $bookmarks[1]->getRouteKey(),
                    'attributes' => [
                        'user_id' => $bookmarks[1]->user_id,
                        'bookmarkable_type' => $bookmarks[1]->bookmarkable_type,
                        'bookmarkable_id' => $bookmarks[1]->bookmarkable_id,
                        'title' => $bookmarks[1]->title,
                        'synopsis' => $bookmarks[1]->synopsis,
                        'notes' => $bookmarks[1]->notes,
                        "bookmarkable" => $bookmarkableArray1
                    ],
                    'links' => [
                        'self' => route('api.v1.bookmarks.show', $bookmarks[1])
                    ]
                ],
                [
                    'type' => 'bookmarks',
                    'id' => (string) $bookmarks[2]->getRouteKey(),
                    'attributes' => [
                        'user_id' => $bookmarks[2]->user_id,
                        'bookmarkable_type' => $bookmarks[2]->bookmarkable_type,
                        'bookmarkable_id' => $bookmarks[2]->bookmarkable_id,
                        'title' => $bookmarks[2]->title,
                        'synopsis' => $bookmarks[2]->synopsis,
                        'notes' => $bookmarks[2]->notes,
                        "bookmarkable" => $bookmarkableArray2
                    ],
                    'links' => [
                        'self' => route('api.v1.bookmarks.show', $bookmarks[2])
                    ]
                ]
            ],
            'links' => [
                'self' => route('api.v1.bookmarks.index')
            ]
        ]);
    }
}
