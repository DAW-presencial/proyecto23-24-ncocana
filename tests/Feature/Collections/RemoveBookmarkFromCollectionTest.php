<?php

namespace Tests\Feature\Collections;

use App\Models\Bookmark;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RemoveBookmarkFromCollectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_remove_bookmark_to_collection(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $collection = Collection::factory()->create([
            'user_id' => $user->id
        ]);

        $bookmark = Bookmark::factory()->create([
            'user_id' => $user->id
        ]);

        $collection->attachTags(['tag1', 'tag2']);
        $bookmark->attachTags(['tag2', 'tag3']);

        $this->postJson(route('api.v1.add_bookmark', [$collection, $bookmark]))->assertContent('{"message":"The bookmark ' . $bookmark->id . ' has been added to the collection ' . $collection->id . '."}');

        $response = $this->getJson(route('api.v1.collections.show', $collection));

        $response->assertHeader(
            'Location',
            route('api.v1.collections.show', $collection)
        );

        $this->assertDatabaseCount('collections', 1);
        $this->assertDatabaseCount('bookmark_collection', 1);
        $this->assertDatabaseCount('taggables', 4);
        $this->assertDatabaseCount('tags', 3);

        $this->deleteJson(route('api.v1.remove_bookmark', [$collection, $bookmark]))->assertContent('{"message":"The bookmark ' . $bookmark->id . ' has been deleted from the collection ' . $collection->id . '."}');

        $this->assertDatabaseCount('collections', 1);
        $this->assertDatabaseCount('bookmark_collection', 0);
        $this->assertDatabaseCount('taggables', 4);
        $this->assertDatabaseCount('tags', 3);
    }
}
