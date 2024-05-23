<?php

namespace Tests\Feature\Collections;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateCollectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_create_collections(): void
    {
        $this->postJson(route('api.v1.collections.store'))
            ->assertUnauthorized();

        // $response->assertJsonApiError();
        
        $this->assertDatabaseCount('collections', 0);
    }

    /** @test */
    public function can_create_collection(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->withoutExceptionHandling();

        $requestData = [
            'bookmarkable_type' => 'Book',
            'name' => 'Nuevo collection',
            'description' => 'Esto es una descripción',
        ];
        // dump($requestData);

        $response = $this->postJson(route('api.v1.collections.store'), $requestData);

        $collection = Collection::first();

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
                    'name' => $collection->name,
                    'description' => $collection->description,
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
    public function can_create_tagged_collection(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->withoutExceptionHandling();

        $requestData = [
            'name' => 'Nuevo collection',
            'description' => 'Esto es una descripción',
            'tags' => ['tag1', 'tag2'],
        ];

        $response = $this->postJson(route('api.v1.collections.store'), $requestData);

        $collection = Collection::first();

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
                    'name' => $collection->name,
                    'description' => $collection->description,
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
                    })->sortBy('id')->values()->all(),
                ],
                'links' => [
                    'self' => route('api.v1.collections.show', $collection)
                ]
            ],
        ]);

        $this->assertDatabaseCount('collections', 1);
        $this->assertDatabaseCount('bookmark_collection', 0);
    }
}
