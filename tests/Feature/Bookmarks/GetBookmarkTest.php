<?php

namespace Tests\Feature\Bookmarks;

use App\Models\Bookmark;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetBookmarkTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }
    
    /** @test */
    public function can_fetch_a_single_bookmark(): void
    {
        $this->withoutExceptionHandling();

        // Retrieve the authenticated user
        $user = User::first();

        $bookmark = Bookmark::factory()->create([
            'user_id' => $user->id
        ]);

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
                    'tags' => [],
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

        // Retrieve the authenticated user
        $user = User::first();

        $bookmarks = Bookmark::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

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
                        'tags' => [],
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
                        'tags' => [],
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
                        'tags' => [],
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

    /** @test */
    public function can_fetch_all_bookmarks_with_given_tags()
    {
        $this->withoutExceptionHandling();

        // Retrieve the authenticated user
        $user = User::first();

        $bookmarks = Bookmark::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $tags = 'tag2,tag3';

        $bookmarks[1]->attachTags(['tag2', 'tag3']);
        $bookmarks[2]->attachTags(['tag3']);

        // Route: "/bookmarks?tags=tag2,tag3"
        $response = $this->getJson(route('api.v1.bookmarks.index', ['tags' => $tags]));
        
        // $bookmarkableArray0 = $bookmarks[0]->bookmarkable->toArray();
        $bookmarkableArray1 = $bookmarks[1]->bookmarkable->toArray();
        $bookmarkableArray2 = $bookmarks[2]->bookmarkable->toArray();

        $response->assertJson([
            'data' => [
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
                        'tags' => $bookmarks[1]->tags->map(function ($tag) {
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
                        "bookmarkable" => $bookmarkableArray1
                    ],
                    'links' => [
                        'self' => route('api.v1.bookmarks.show', $bookmarks[1])
                    ]
                ],
                // [
                //     'type' => 'bookmarks',
                //     'id' => (string) $bookmarks[2]->getRouteKey(),
                //     'attributes' => [
                //         'user_id' => $bookmarks[2]->user_id,
                //         'bookmarkable_type' => $bookmarks[2]->bookmarkable_type,
                //         'bookmarkable_id' => $bookmarks[2]->bookmarkable_id,
                //         'title' => $bookmarks[2]->title,
                //         'synopsis' => $bookmarks[2]->synopsis,
                //         'notes' => $bookmarks[2]->notes,
                //         'tags' => $bookmarks[2]->tags->map(function ($tag) {
                //             return [
                //                 'id' => $tag->id,
                //                 'name' => $tag->name,
                //                 'slug' => $tag->slug,
                //                 'pivot' => [
                //                     'taggable_type' => $tag->pivot->taggable_type,
                //                     'taggable_id' => $tag->pivot->taggable_id,
                //                     'tag_id' => $tag->pivot->tag_id,
                //                 ]
                //             ];
                //         })->sortBy('id')->values()->all(),
                //         "bookmarkable" => $bookmarkableArray2
                //     ],
                //     'links' => [
                //         'self' => route('api.v1.bookmarks.show', $bookmarks[2])
                //     ]
                // ]
            ],
            'links' => [
                'self' => route('api.v1.bookmarks.index')
            ]
        ]);
    }

    /** @test */
    public function can_fetch_all_bookmarks_with_different_edit_date()
    {
        $this->withoutExceptionHandling();

        // Retrieve the authenticated user
        $user = User::first();

        $bookmark0 = Bookmark::factory()->create([
            'updated_at' => now()->year(2021),
            'user_id' => $user->id,
        ]);
        
        $bookmark1 = Bookmark::factory()->create([
            'updated_at' => now()->year(2022),
            'user_id' => $user->id,
        ]);
        
        $bookmark2 = Bookmark::factory()->create([
            'updated_at' => now()->year(2023),
            'user_id' => $user->id,
        ]);

        $response = $this->getJson(route('api.v1.bookmarks.index'));
        
        $bookmarkableArray0 = $bookmark0->bookmarkable->toArray();
        $bookmarkableArray1 = $bookmark1->bookmarkable->toArray();
        $bookmarkableArray2 = $bookmark2->bookmarkable->toArray();

        $response->assertJson([
            'data' => [
                [
                    'type' => 'bookmarks',
                    'id' => (string) $bookmark2->getRouteKey(),
                    'attributes' => [
                        'user_id' => $bookmark2->user_id,
                        'bookmarkable_type' => $bookmark2->bookmarkable_type,
                        'bookmarkable_id' => $bookmark2->bookmarkable_id,
                        'title' => $bookmark2->title,
                        'synopsis' => $bookmark2->synopsis,
                        'notes' => $bookmark2->notes,
                        'tags' => [],
                        "bookmarkable" => $bookmarkableArray2
                    ],
                    'links' => [
                        'self' => route('api.v1.bookmarks.show', $bookmark2)
                    ]
                ],
                [
                    'type' => 'bookmarks',
                    'id' => (string) $bookmark1->getRouteKey(),
                    'attributes' => [
                        'user_id' => $bookmark1->user_id,
                        'bookmarkable_type' => $bookmark1->bookmarkable_type,
                        'bookmarkable_id' => $bookmark1->bookmarkable_id,
                        'title' => $bookmark1->title,
                        'synopsis' => $bookmark1->synopsis,
                        'notes' => $bookmark1->notes,
                        'tags' => [],
                        "bookmarkable" => $bookmarkableArray1
                    ],
                    'links' => [
                        'self' => route('api.v1.bookmarks.show', $bookmark1)
                    ]
                ],
                [
                    'type' => 'bookmarks',
                    'id' => (string) $bookmark0->getRouteKey(),
                    'attributes' => [
                        'user_id' => $bookmark0->user_id,
                        'bookmarkable_type' => $bookmark0->bookmarkable_type,
                        'bookmarkable_id' => $bookmark0->bookmarkable_id,
                        'title' => $bookmark0->title,
                        'synopsis' => $bookmark0->synopsis,
                        'notes' => $bookmark0->notes,
                        'tags' => [],
                        "bookmarkable" => $bookmarkableArray0
                    ],
                    'links' => [
                        'self' => route('api.v1.bookmarks.show', $bookmark0)
                    ]
                ]
            ],
            'links' => [
                'self' => route('api.v1.bookmarks.index')
            ]
        ]);
    }
}
