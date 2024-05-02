<?php

namespace Tests\Feature\Bookmarks;

use App\Models\Bookmark;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SortBookmarkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_sort_bookmarks_by_bookmarkable_type(): void
    {
        Bookmark::factory()->ofType('App\Models\Movie')->create();
        Bookmark::factory()->ofType('App\Models\Book')->create();
        Bookmark::factory()->ofType('App\Models\Series')->create();
        Bookmark::factory()->ofType('App\Models\Fanfic')->create();

        // Query Sort = "/bookmarks?sort=bookmarkable_type"
        $url = route('api.v1.bookmarks.index', ['sort' => 'bookmarkable_type']);
        // dd($url);
        
        $this->getJson($url)->assertSeeInOrder([
            'App\\\Models\\\Book',
            'App\\\Models\\\Fanfic',
            'App\\\Models\\\Movie',
            'App\\\Models\\\Series',
        ]);
        
        $this->assertDatabaseCount('bookmarks', 4);
        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseCount('fanfics', 1);
        $this->assertDatabaseCount('series', 1);
        $this->assertDatabaseCount('movies', 1);
    }
}
