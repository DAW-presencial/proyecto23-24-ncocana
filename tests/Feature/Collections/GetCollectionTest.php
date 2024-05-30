<?php

namespace Tests\Feature\Collections;

use App\Models\Bookmark;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetCollectionTest extends TestCase
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
    public function can_fetch_a_single_collection(): void
    {
        $this->withoutExceptionHandling();

        // Retrieve the authenticated user
        $user = User::first();

        $collection = Collection::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->getJson(route('api.v1.collections.show', $collection));

        $response->assertExactJson([
            'data' => [
                'type' => 'collections',
                'id' => (string) $collection->getRouteKey(),
                'attributes' => [
                    'user_id' => $collection->user_id,
                    'name' => $collection->name,
                    'description' => $collection->description,
                    'bookmarks' => [],
                    'tags' => [],
                ],
                'links' => [
                    'self' => url(route('api.v1.collections.show', $collection))
                ]
            ]
        ]);
    }

    /** @test */
    public function can_fetch_all_collections()
    {
        $this->withoutExceptionHandling();

        // Retrieve the authenticated user
        $user = User::first();

        $collections = Collection::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $response = $this->getJson(route('api.v1.collections.index'));

        $response->assertJson([
            'data' => [
                [
                    'type' => 'collections',
                    'id' => (string) $collections[0]->getRouteKey(),
                    'attributes' => [
                        'user_id' => $collections[0]->user_id,
                        'name' => $collections[0]->name,
                        'description' => $collections[0]->description,
                        'bookmarks' => [],
                        'tags' => [],
                    ],
                    'links' => [
                        'self' => route('api.v1.collections.show', $collections[0])
                    ]
                ],
                [
                    'type' => 'collections',
                    'id' => (string) $collections[1]->getRouteKey(),
                    'attributes' => [
                        'user_id' => $collections[1]->user_id,
                        'name' => $collections[1]->name,
                        'description' => $collections[1]->description,
                        'bookmarks' => [],
                        'tags' => [],
                    ],
                    'links' => [
                        'self' => route('api.v1.collections.show', $collections[1])
                    ]
                ],
                [
                    'type' => 'collections',
                    'id' => (string) $collections[2]->getRouteKey(),
                    'attributes' => [
                        'user_id' => $collections[2]->user_id,
                        'name' => $collections[2]->name,
                        'description' => $collections[2]->description,
                        'bookmarks' => [],
                        'tags' => [],
                    ],
                    'links' => [
                        'self' => route('api.v1.collections.show', $collections[2])
                    ]
                ]
            ],
            'links' => [
                'self' => route('api.v1.collections.index')
            ]
        ]);
    }

    /** @test */
    public function can_fetch_all_collections_with_given_tags()
    {
        $this->withoutExceptionHandling();

        // Retrieve the authenticated user
        $user = User::first();

        $collections = Collection::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $tags = 'tag2,tag3';

        $collections[1]->attachTags(['tag2', 'tag3']);
        $collections[2]->attachTags(['tag3']);

        // Route: "/collections?tags=tag2,tag3"
        $response = $this->getJson(route('api.v1.collections.index', ['tags' => $tags]));

        $response->assertJson([
            'data' => [
                [
                    'type' => 'collections',
                    'id' => (string) $collections[1]->getRouteKey(),
                    'attributes' => [
                        'user_id' => $collections[1]->user_id,
                        'name' => $collections[1]->name,
                        'description' => $collections[1]->description,
                        'bookmarks' => [],
                        'tags' => $collections[1]->tags->map(function ($tag) {
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
                    ],
                    'links' => [
                        'self' => route('api.v1.collections.show', $collections[1])
                    ]
                ],
                // [
                //     'type' => 'collections',
                //     'id' => (string) $collections[2]->getRouteKey(),
                //     'attributes' => [
                //         'user_id' => $collections[2]->user_id,
                //         'name' => $collections[2]->name,
                //         'description' => $collections[2]->description,
                //         'bookmarks' => [],
                //         'tags' => $collections[2]->tags->map(function ($tag) {
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
                //     ],
                //     'links' => [
                //         'self' => route('api.v1.collections.show', $collections[2])
                //     ]
                // ]
            ],
            'links' => [
                'self' => route('api.v1.collections.index')
            ]
        ]);
    }

    /** @test */
    public function can_fetch_all_collections_with_different_edit_date()
    {
        $this->withoutExceptionHandling();

        // Retrieve the authenticated user
        $user = User::first();

        $collection0 = Collection::factory()->create([
            'updated_at' => now()->year(2021),
            'user_id' => $user->id,
        ]);
        
        $collection1 = Collection::factory()->create([
            'updated_at' => now()->year(2022),
            'user_id' => $user->id,
        ]);
        
        $collection2 = Collection::factory()->create([
            'updated_at' => now()->year(2023),
            'user_id' => $user->id,
        ]);

        $response = $this->getJson(route('api.v1.collections.index'));

        $response->assertJson([
            'data' => [
                [
                    'type' => 'collections',
                    'id' => (string) $collection2->getRouteKey(),
                    'attributes' => [
                        'user_id' => $collection2->user_id,
                        'name' => $collection2->name,
                        'description' => $collection2->description,
                        'bookmarks' => [],
                        'tags' => [],
                    ],
                    'links' => [
                        'self' => route('api.v1.collections.show', $collection2)
                    ]
                ],
                [
                    'type' => 'collections',
                    'id' => (string) $collection1->getRouteKey(),
                    'attributes' => [
                        'user_id' => $collection1->user_id,
                        'name' => $collection1->name,
                        'description' => $collection1->description,
                        'bookmarks' => [],
                        'tags' => [],
                    ],
                    'links' => [
                        'self' => route('api.v1.collections.show', $collection1)
                    ]
                ],
                [
                    'type' => 'collections',
                    'id' => (string) $collection0->getRouteKey(),
                    'attributes' => [
                        'user_id' => $collection0->user_id,
                        'name' => $collection0->name,
                        'description' => $collection0->description,
                        'bookmarks' => [],
                        'tags' => [],
                    ],
                    'links' => [
                        'self' => route('api.v1.collections.show', $collection0)
                    ]
                ]
            ],
            'links' => [
                'self' => route('api.v1.collections.index')
            ]
        ]);
    }

    /** @test */
    public function can_fetch_all_collections_with_bookmarks()
    {
        $this->withoutExceptionHandling();

        // Retrieve the authenticated user
        $user = User::first();

        $collections = Collection::factory()
        ->has(Bookmark::factory()->state(function (array $attributes, Collection $collection) use ($user) {
            return ['user_id' => $user->id];
        })->count(1))
        ->count(3)
        ->create([
            'user_id' => $user->id
        ]);

        $response = $this->getJson(route('api.v1.collections.index'));
        
        $bookmarksArray0 = $collections[0]->bookmarks->toArray();
        $bookmarksArray1 = $collections[1]->bookmarks->toArray();
        $bookmarksArray2 = $collections[2]->bookmarks->toArray();

        $response->assertJson([
            'data' => [
                [
                    'type' => 'collections',
                    'id' => (string) $collections[0]->getRouteKey(),
                    'attributes' => [
                        'user_id' => $collections[0]->user_id,
                        'name' => $collections[0]->name,
                        'description' => $collections[0]->description,
                        'bookmarks' => $bookmarksArray0,
                        'tags' => [],
                    ],
                    'links' => [
                        'self' => route('api.v1.collections.show', $collections[0])
                    ]
                ],
                [
                    'type' => 'collections',
                    'id' => (string) $collections[1]->getRouteKey(),
                    'attributes' => [
                        'user_id' => $collections[1]->user_id,
                        'name' => $collections[1]->name,
                        'description' => $collections[1]->description,
                        'bookmarks' => $bookmarksArray1,
                        'tags' => [],
                    ],
                    'links' => [
                        'self' => route('api.v1.collections.show', $collections[1])
                    ]
                ],
                [
                    'type' => 'collections',
                    'id' => (string) $collections[2]->getRouteKey(),
                    'attributes' => [
                        'user_id' => $collections[2]->user_id,
                        'name' => $collections[2]->name,
                        'description' => $collections[2]->description,
                        'bookmarks' => $bookmarksArray2,
                        'tags' => [],
                    ],
                    'links' => [
                        'self' => route('api.v1.collections.show', $collections[2])
                    ]
                ]
            ],
            'links' => [
                'self' => route('api.v1.collections.index')
            ]
        ]);
        
        $this->assertDatabaseCount('collections', 3);
        $this->assertDatabaseCount('bookmark_collection', 3);
    }
}
