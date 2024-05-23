<?php

namespace Tests\Feature\Collections;

use App\Models\Bookmark;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteCollectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_delete_collection(): void
    {
        $collection = Collection::factory()->create();

        $this->deleteJson(route('api.v1.collections.destroy', $collection))
            ->assertUnauthorized();
    }
    
    /** @test */
    public function can_delete_collection(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a collection
        $collection = Collection::factory()->create();

        // Delete the collection
        $this->deleteJson(route('api.v1.collections.destroy', $collection))->assertOk();

        // Assert that there are no records in the collections table
        $this->assertDatabaseCount('collections', 0);

        // Assert that there are no records in the pivot table
        $this->assertDatabaseCount('bookmark_collection', 0);
    }
    
    /** @test */
    public function can_delete_tagged_collection(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a collection
        $collection = Collection::factory()->create();

        // Attach tags to collection
        $collection->attachTags(['tag1', 'tag2']);

        // Delete the collection
        $this->deleteJson(route('api.v1.collections.destroy', $collection))->assertOk();

        // Assert that there are no records in the collections table
        $this->assertDatabaseCount('collections', 0);

        // Assert that there are no records in the pivot table
        $this->assertDatabaseCount('bookmark_collection', 0);
        
        // Assert current records in tags tables
        // The taggable relation deletes itself on cascade, but the tags remain for future use
        $this->assertDatabaseCount('tags', 2);
        $this->assertDatabaseCount('taggables', 0);
    }
    
    /** @test */
    public function can_delete_collection_with_bookmarks(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a collection
        $collection = Collection::factory()->create();

        // Create a collection
        $bookmarks = Bookmark::factory()->count(2)->create();

        // Add bookmarks to the collection
        $this->postJson(route('api.v1.add_bookmark', [$collection, $bookmarks[0]]))->assertContent('{"message":"The bookmark ' . $bookmarks[0]->id . ' has been added to the collection ' . $collection->id . '."}');
        $this->postJson(route('api.v1.add_bookmark', [$collection, $bookmarks[1]]))->assertContent('{"message":"The bookmark ' . $bookmarks[1]->id . ' has been added to the collection ' . $collection->id . '."}');

        $this->assertDatabaseCount('collections', 1);
        $this->assertDatabaseCount('bookmark_collection', 2);

        // Delete the collection
        $this->deleteJson(route('api.v1.collections.destroy', $collection))->assertOk();

        // Assert that there are no records in the collections table
        $this->assertDatabaseCount('collections', 0);

        // Assert that there are no records in the pivot table
        $this->assertDatabaseCount('bookmark_collection', 0);
    }
}
