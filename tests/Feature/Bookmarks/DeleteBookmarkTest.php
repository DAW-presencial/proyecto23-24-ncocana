<?php

namespace Tests\Feature\Bookmarks;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteBookmarkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_delete_bookmark(): void
    {
        $bookmark = Bookmark::factory()->create();

        $this->deleteJson(route('api.v1.bookmarks.destroy', $bookmark))
            ->assertUnauthorized();
    }
    
    /** @test */
    public function can_delete_bookmark(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a bookmark
        $bookmark = Bookmark::factory()->create();

        // Delete the bookmark
        $this->deleteJson(route('api.v1.bookmarks.destroy', $bookmark))->assertContent('{"message":"Bookmark deleted successfully"}');

        // Get the bookmarkable type
        $bookmarkableType = $bookmark->bookmarkable_type;

        // Determine the table name based on the bookmarkable type
        $tableName = Str::plural(strtolower(class_basename($bookmarkableType)));

        // Assert that there are no records in the bookmarks table
        $this->assertDatabaseCount('bookmarks', 0);

        // Assert that there are no records in the table associated with the bookmarkable type
        $this->assertDatabaseCount($tableName, 0);
    }
    
    /** @test */
    public function can_delete_tagged_bookmark(): void
    {
        // Creating and authenticating a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a bookmark
        $bookmark = Bookmark::factory()->create();

        // Attach tags to bookmark
        $bookmark->attachTags(['tag1', 'tag2']);

        // Delete the bookmark
        $this->deleteJson(route('api.v1.bookmarks.destroy', $bookmark))->assertContent('{"message":"Bookmark deleted successfully"}');

        // Get the bookmarkable type
        $bookmarkableType = $bookmark->bookmarkable_type;

        // Determine the table name based on the bookmarkable type
        $tableName = Str::plural(strtolower(class_basename($bookmarkableType)));

        // Assert that there are no records in the bookmarks table
        $this->assertDatabaseCount('bookmarks', 0);

        // Assert that there are no records in the table associated with the bookmarkable type
        $this->assertDatabaseCount($tableName, 0);
        
        // Assert current records in tags tables
        // The taggable relation deletes itself on cascade, but the tags remain for future use
        $this->assertDatabaseCount('tags', 2);
        $this->assertDatabaseCount('taggables', 0);
    }
}
