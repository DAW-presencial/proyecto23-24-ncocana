<?php

namespace Tests\Feature\Feature;

use App\Models\Bookmark;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AddBookmarkToCollectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_add_bookmark_to_collection(): void
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

        // $bookmarkableArray = $bookmark->bookmarkable->toArray();

        // $response->assertExactJson([
        //     'data' => [
        //         'type' => 'collections',
        //         'id' => (string) $collection->getRouteKey(),
        //         'attributes' => [
        //             'user_id' => $collection->user_id,
        //             'name' => $collection->name,
        //             'description' => $collection->description,
        //             'bookmarks' => $collection->bookmarks->map(function ($bookmark) use ($bookmarkableArray) {
        //                 return [
        //                     'id' => $bookmark->id,
        //                     'user_id' => $bookmark->user_id,
        //                     'title' => $bookmark->title,
        //                     'synopsis' => $bookmark->synopsis,
        //                     'notes' => $bookmark->notes,
        //                     'bookmarkable' => $bookmarkableArray,
        //                     'tags' => $bookmark->tags->map(function ($tag) {
        //                         return [
        //                             'id' => $tag->id,
        //                             'name' => $tag->name,
        //                             'slug' => $tag->slug,
        //                         ];
        //                     })->sortBy('id')->values()->all(),
        //                 ];
        //             })->sortBy('id')->values()->all(),
        //             'tags' => $collection->tags->map(function ($tag) {
        //                 return [
        //                     'id' => $tag->id,
        //                     'name' => $tag->name,
        //                     'slug' => $tag->slug,
        //                     'pivot' => [
        //                         'taggable_type' => $tag->pivot->taggable_type,
        //                         'taggable_id' => $tag->pivot->taggable_id,
        //                         'tag_id' => $tag->pivot->tag_id,
        //                     ]
        //                 ];
        //             })->sortBy('id')->values()->all(),
        //         ],
        //         'links' => [
        //             'self' => route('api.v1.collections.show', $collection)
        //         ]
        //     ],
        // ]);

        $this->assertDatabaseCount('collections', 1);
        $this->assertDatabaseCount('bookmark_collection', 1);
        $this->assertDatabaseCount('taggables', 4);
        $this->assertDatabaseCount('tags', 3);
    }
}
