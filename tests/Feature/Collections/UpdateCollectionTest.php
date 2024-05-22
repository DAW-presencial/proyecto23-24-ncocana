<?php

namespace Tests\Feature\Collections;

use App\Models\Bookmark;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateCollectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_update_collections(): void
    {
        $collection = Collection::factory()->create();

        $this->patchJson(route('api.v1.collections.update', $collection))
            ->assertUnauthorized();
    }

    /** @test */
    public function can_update_collections(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $collection = Collection::factory()->create();

        $requestData = [
            'name' => 'Updated collection',
            'description' => 'Esto es una descripción actualizada',
        ];

        $response = $this->patchJson(route('api.v1.collections.update', $collection), $requestData)->assertOk();

        $response->assertHeader(
            'Location',
            route('api.v1.collections.show', $collection)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'collections',
                'id' => (string) $collection->getRouteKey(),
                'attributes' => [
                    'user_id' => $collection->user_id,
                    'name' => $requestData['name'],
                    'description' => $requestData['description'],
                    'bookmarks' => [],
                    'tags' => [],
                ],
                'links' => [
                    'self' => route('api.v1.collections.show', $collection)
                ]
            ],
        ]);

        $this->assertDatabaseCount('collections', 1);
        $this->assertDatabaseCount('bookmark_collection', 0);
    }

    /** @test */
    public function can_update_tagged_collection(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $collection = Collection::factory()->create();

        $collection->attachTag('tag1');

        $requestData = [
            'name' => 'Updated collection',
            'description' => 'Esto es una descripción actualizada',
            'tags' => ['tag2', 'tag3'],
        ];

        $response = $this->patchJson(route('api.v1.collections.update', $collection), $requestData)->assertOk();

        $response->assertHeader(
            'Location',
            route('api.v1.collections.show', $collection)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'collections',
                'id' => (string) $collection->getRouteKey(),
                'attributes' => [
                    'user_id' => $collection->user_id,
                    'name' => $requestData['name'],
                    'description' => $requestData['description'],
                    'bookmarks' => [],
                    'tags' => $collection->tags->map(function ($tag) {
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
                    })->sortBy('id')->values()->all()
                ],
                'links' => [
                    'self' => route('api.v1.collections.show', $collection)
                ]
            ],
        ]);

        $this->assertDatabaseCount('collections', 1);
        $this->assertDatabaseCount('bookmark_collection', 0);
        $this->assertDatabaseCount('tags', 3);
        $this->assertDatabaseCount('taggables', 2);
    }

    /** @test */
    public function can_update_collections_with_bookmarks(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $collection = Collection::factory()->create();

        $requestData = [
            'name' => 'Updated collection',
            'description' => 'Esto es una descripción actualizada',
        ];

        $bookmark = Bookmark::factory()->create([
            'user_id' => $user->id
        ]);

        $this->postJson(route('api.v1.add_bookmark', [$collection, $bookmark]))->assertContent('{"message":"The bookmark ' . $bookmark->id . ' has been added to the collection ' . $collection->id . '."}');

        $bookmarksArray = $collection->bookmarks->toArray();

        $response = $this->patchJson(route('api.v1.collections.update', $collection), $requestData)->assertOk();

        $response->assertHeader(
            'Location',
            route('api.v1.collections.show', $collection)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'collections',
                'id' => (string) $collection->getRouteKey(),
                'attributes' => [
                    'user_id' => $collection->user_id,
                    'name' => $requestData['name'],
                    'description' => $requestData['description'],
                    'bookmarks' => $bookmarksArray,
                    'tags' => [],
                ],
                'links' => [
                    'self' => route('api.v1.collections.show', $collection)
                ]
            ],
        ]);

        $this->assertDatabaseCount('collections', 1);
        $this->assertDatabaseCount('bookmark_collection', 1);
    }
}
