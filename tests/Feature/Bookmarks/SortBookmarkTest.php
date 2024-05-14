<?php

namespace Tests\Feature\Bookmarks;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SortBookmarkTest extends TestCase
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
    public function can_sort_bookmarks_by_bookmarkable_type(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Bookmark::factory()->ofType('App\Models\Movie')->create([
            'user_id' => $user->id
        ]);
        Bookmark::factory()->ofType('App\Models\Fanfic')->create([
            'user_id' => $user->id
        ]);
        Bookmark::factory()->ofType('App\Models\Book')->create([
            'user_id' => $user->id
        ]);
        Bookmark::factory()->ofType('App\Models\Series')->create([
            'user_id' => $user->id
        ]);
        Bookmark::factory()->ofType('App\Models\Fanfic')->create([
            'user_id' => $user->id
        ]);

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
        // Retrieve the authenticated user
        $user = User::first();

        Bookmark::factory()->ofType('App\Models\Movie')->create([
            'user_id' => $user->id
        ]);
        Bookmark::factory()->ofType('App\Models\Fanfic')->create([
            'user_id' => $user->id
        ]);
        Bookmark::factory()->ofType('App\Models\Book')->create([
            'user_id' => $user->id
        ]);
        Bookmark::factory()->ofType('App\Models\Series')->create([
            'user_id' => $user->id
        ]);
        Bookmark::factory()->ofType('App\Models\Fanfic')->create([
            'user_id' => $user->id
        ]);

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
        // Retrieve the authenticated user
        $user = User::first();

        $bookmark1 = Bookmark::factory()->ofType('App\Models\Movie')->create([
            'created_at' => now()->year(2023),
            'user_id' => $user->id,
        ]);
        $bookmark2 = Bookmark::factory()->ofType('App\Models\Fanfic')->create([
            'created_at' => now()->year(2021),
            'user_id' => $user->id,
        ]);
        $bookmark3 = Bookmark::factory()->ofType('App\Models\Book')->create([
            'created_at' => now()->month(3),
            'user_id' => $user->id,
        ]);
        $bookmark4 = Bookmark::factory()->ofType('App\Models\Series')->create([
            'created_at' => now()->month(1),
            'user_id' => $user->id,
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
    public function can_sort_bookmarks_by_bookmarkable_type_and_title(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Bookmark::factory()->ofType('App\Models\Movie')->create([
            'user_id' => $user->id,
            'title' => 'B title',
        ]);
        Bookmark::factory()->ofType('App\Models\Fanfic')->create([
            'user_id' => $user->id,
            'title' => 'A title',
        ]);
        Bookmark::factory()->ofType('App\Models\Book')->create([
            'user_id' => $user->id,
            'title' => 'D title',
        ]);
        Bookmark::factory()->ofType('App\Models\Series')->create([
            'user_id' => $user->id,
            'title' => 'E title',
        ]);
        Bookmark::factory()->ofType('App\Models\Fanfic')->create([
            'user_id' => $user->id,
            'title' => 'C title',
        ]);

        // Query Sort = "/bookmarks?sort=-bookmarkable_type,title"
        $url = route('api.v1.bookmarks.index', ['sort' => '-bookmarkable_type,title']);
        
        $this->getJson($url)->assertSeeInOrder([
            'E title',
            'B title',
            'A title',
            'C title',
            'D title',
        ]);
        
        $this->assertDatabaseCount('bookmarks', 5);
        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseCount('fanfics', 2);
        $this->assertDatabaseCount('series', 1);
        $this->assertDatabaseCount('movies', 1);
    }

    /** @test */
    public function can_sort_bookmarks_by_title(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Bookmark::factory()->ofType('App\Models\Movie')->create([
            'title' => 'B title',
            'user_id' => $user->id,
        ]);
        Bookmark::factory()->ofType('App\Models\Fanfic')->create([
            'title' => 'A title',
            'user_id' => $user->id,
        ]);
        Bookmark::factory()->ofType('App\Models\Book')->create([
            'title' => 'D title',
            'user_id' => $user->id,
        ]);
        Bookmark::factory()->ofType('App\Models\Series')->create([
            'title' => 'C title',
            'user_id' => $user->id,
        ]);

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
        // Retrieve the authenticated user
        $user = User::first();

        Bookmark::factory(3)->create([
            'user_id' => $user->id
        ]);

        // Query Sort = "/bookmarks?sort=unknown"
        $url = route('api.v1.bookmarks.index', ['sort' => 'unknown']);
        
        $this->getJson($url)->assertStatus(400);
        $this->assertDatabaseCount('bookmarks', 3);
    }
}
