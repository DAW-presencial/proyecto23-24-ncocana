<?php

namespace Tests\Feature\Bookmarks;

use App\Models\Bookmark;
use Carbon\Carbon;
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
        Bookmark::factory()->ofType('App\Models\Fanfic')->create();
        Bookmark::factory()->ofType('App\Models\Book')->create();
        Bookmark::factory()->ofType('App\Models\Series')->create();
        Bookmark::factory()->ofType('App\Models\Fanfic')->create();

        // Query Sort = "/bookmarks?sort=bookmarkable_type"
        $url = route('api.v1.bookmarks.index', ['sort' => 'bookmarkable_type']);
        
        $this->getJson($url)->assertSeeInOrder([
            'App\\\Models\\\Book',
            'App\\\Models\\\Fanfic',
            'App\\\Models\\\Fanfic',
            'App\\\Models\\\Movie',
            'App\\\Models\\\Series',
        ]);
        
        $this->assertDatabaseCount('bookmarks', 5);
        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseCount('fanfics', 2);
        $this->assertDatabaseCount('series', 1);
        $this->assertDatabaseCount('movies', 1);
    }

    /** @test */
    public function can_sort_bookmarks_by_bookmarkable_type_descending(): void
    {
        Bookmark::factory()->ofType('App\Models\Movie')->create();
        Bookmark::factory()->ofType('App\Models\Fanfic')->create();
        Bookmark::factory()->ofType('App\Models\Book')->create();
        Bookmark::factory()->ofType('App\Models\Series')->create();
        Bookmark::factory()->ofType('App\Models\Fanfic')->create();

        // Query Sort = "/bookmarks?sort=-bookmarkable_type"
        $url = route('api.v1.bookmarks.index', ['sort' => '-bookmarkable_type']);
        
        $this->getJson($url)->assertSeeInOrder([
            'App\\\Models\\\Series',
            'App\\\Models\\\Movie',
            'App\\\Models\\\Fanfic',
            'App\\\Models\\\Fanfic',
            'App\\\Models\\\Book',
        ]);
        
        $this->assertDatabaseCount('bookmarks', 5);
        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseCount('fanfics', 2);
        $this->assertDatabaseCount('series', 1);
        $this->assertDatabaseCount('movies', 1);
    }

    /** @test */
    public function can_sort_bookmarks_by_created_at_descending(): void
    {
        $bookmark1 = Bookmark::factory()->ofType('App\Models\Movie')->create([
            'created_at' => now()->year(2023)
        ]);
        $bookmark2 = Bookmark::factory()->ofType('App\Models\Fanfic')->create([
            'created_at' => now()->year(2021)
        ]);
        $bookmark3 = Bookmark::factory()->ofType('App\Models\Book')->create([
            'created_at' => now()->month(3)
        ]);
        $bookmark4 = Bookmark::factory()->ofType('App\Models\Series')->create([
            'created_at' => now()->month(1)
        ]);

        // Query Sort = "/bookmarks?sort=-created_at"
        $url = route('api.v1.bookmarks.index', ['sort' => '-created_at']);
    
        $this->getJson($url)->assertSeeInOrder([
            $bookmark3->title,
            $bookmark4->title,
            $bookmark1->title,
            $bookmark2->title,
        ]);
        
        $this->assertDatabaseCount('bookmarks', 4);
        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseCount('fanfics', 1);
        $this->assertDatabaseCount('series', 1);
        $this->assertDatabaseCount('movies', 1);
    }

    /** @test */
    public function can_sort_bookmarks_by_bookmarkable_type_and_user_id(): void
    {
        Bookmark::factory()->ofType('App\Models\Movie')->create();
        Bookmark::factory()->ofType('App\Models\Fanfic')->create();
        Bookmark::factory()->ofType('App\Models\Book')->create();
        Bookmark::factory()->ofType('App\Models\Series')->create();
        Bookmark::factory()->ofType('App\Models\Fanfic')->create();

        // Query Sort = "/bookmarks?sort=-bookmarkable_type,user_id"
        $url = route('api.v1.bookmarks.index', ['sort' => '-bookmarkable_type,user_id']);
        
        $response = $this->getJson($url);

        $userIds = collect($response->json()['data'])->pluck('attributes.user_id')->toArray();

        $this->assertSame([4, 1, 2, 5, 3], $userIds);
        
        $this->assertDatabaseCount('bookmarks', 5);
        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseCount('fanfics', 2);
        $this->assertDatabaseCount('series', 1);
        $this->assertDatabaseCount('movies', 1);
    }

    /** @test */
    public function can_sort_bookmarks_by_title(): void
    {
        Bookmark::factory()->ofType('App\Models\Movie')->create(['title' => 'B title']);
        Bookmark::factory()->ofType('App\Models\Fanfic')->create(['title' => 'A title']);
        Bookmark::factory()->ofType('App\Models\Book')->create(['title' => 'D title']);
        Bookmark::factory()->ofType('App\Models\Series')->create(['title' => 'C title']);

        // Query Sort = "/bookmarks?sort=title"
        $url = route('api.v1.bookmarks.index', ['sort' => 'title']);
        
        $this->getJson($url)->assertSeeInOrder([
            'A title',
            'B title',
            'C title',
            'D title',
        ]);
        
        $this->assertDatabaseCount('bookmarks', 4);
        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseCount('fanfics', 1);
        $this->assertDatabaseCount('series', 1);
        $this->assertDatabaseCount('movies', 1);
    }
    
    /** @test */
    public function cannot_sort_articles_by_unknown_fields(): void
    {
        Bookmark::factory(3)->create();

        // Query Sort = "/bookmarks?sort=unknown"
        $url = route('api.v1.bookmarks.index', ['sort' => 'unknown']);
        
        $this->getJson($url)->assertStatus(400);
        $this->assertDatabaseCount('bookmarks', 3);
    }
}
