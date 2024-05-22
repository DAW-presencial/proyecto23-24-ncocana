<?php

namespace Tests\Feature\Bookmarks;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FilterBookmarkTest extends TestCase
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
    public function can_filter_bookmarks_by_bookmarkable_type(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Bookmark::factory()->ofType('App\Models\Movie')->create([
            'title' => 'First bookmark',
            'user_id' => $user->id,
        ]);
        
        Bookmark::factory()->ofType('App\Models\Series')->create([
            'title' => 'Other bookmark',
            'user_id' => $user->id,
        ]);

        // Endpoint: "bookmarks?filter[bookmarkable_type]=movie"
        $url = route('api.v1.bookmarks.index', [
            'filter' => [
                'bookmarkable_type' => 'movie'
            ]
        ]);
        // dd($this->getJson($url));

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('First bookmark')
            ->assertDontSee('Other bookmark');
    }
    
    /** @test */
    public function can_filter_bookmarks_by_title(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Bookmark::factory()->create([
            'title' => 'My kingdom come undone',
            'user_id' => $user->id,
        ]);
        
        Bookmark::factory()->create([
            'title' => 'Other bookmark',
            'user_id' => $user->id,
        ]);

        // Endpoint: "bookmarks?filter[title]=kingdom"
        $url = route('api.v1.bookmarks.index', [
            'filter' => [
                'title' => 'kingdom'
            ]
        ]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('My kingdom come undone')
            ->assertDontSee('Other bookmark');
    }
    
    /** @test */
    public function can_filter_bookmarks_by_synopsis(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Bookmark::factory()->create([
            'synopsis' => 'Go to Liyue and build a false identity as a backup plan, they said. It\'d be easy, they said. Pantalone would like to express that everything the Fatui Harbingers say is bullshit. Otherwise known as the "pantalone is baizhu and i will die on this hill" story',
            'user_id' => $user->id,
        ]);
        
        Bookmark::factory()->create([
            'synopsis' => 'Other bookmark',
            'user_id' => $user->id,
        ]);

        // Endpoint: "bookmarks?filter[synopsis]=pantalone%20is%20baizhu"
        $url = route('api.v1.bookmarks.index', [
            'filter' => [
                'synopsis' => 'pantalone is baizhu'
            ]
        ]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Go to Liyue and build a false identity as a backup plan, they said. It\'d be easy, they said. Pantalone would like to express that everything the Fatui Harbingers say is bullshit. Otherwise known as the \"pantalone is baizhu and i will die on this hill\" story', escape: false)
            ->assertDontSee('Other bookmark');
    }
    
    /** @test */
    public function can_filter_bookmarks_by_notes(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Bookmark::factory()->create([
            'notes' => 'Fluff for my heart',
            'user_id' => $user->id,
        ]);
        
        Bookmark::factory()->create([
            'notes' => 'Other bookmark',
            'user_id' => $user->id,
        ]);

        // Endpoint: "bookmarks?filter[notes]=fluff"
        $url = route('api.v1.bookmarks.index', [
            'filter' => [
                'notes' => 'fluff'
            ]
        ]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Fluff for my heart')
            ->assertDontSee('Other bookmark');
    }
    
    /** @test */
    public function can_filter_bookmarks_by_year(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Bookmark::factory()->create([
            'title' => 'Bookmark from 2021',
            'updated_at' => now()->year(2021),
            'user_id' => $user->id,
        ]);
        
        Bookmark::factory()->create([
            'title' => 'Bookmark from 2022',
            'updated_at' => now()->year(2022),
            'user_id' => $user->id,
        ]);

        // Endpoint: "bookmarks?filter[year]=2021"
        $url = route('api.v1.bookmarks.index', [
            'filter' => [
                'year' => '2021'
            ]
        ]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Bookmark from 2021')
            ->assertDontSee('Bookmark from 2022');
    }
    
    /** @test */
    public function can_filter_bookmarks_by_month(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Bookmark::factory()->create([
            'title' => 'Bookmark from month 3',
            'updated_at' => now()->month(3),
            'user_id' => $user->id,
        ]);
        
        Bookmark::factory()->create([
            'title' => 'Another Bookmark from month 3',
            'updated_at' => now()->month(3),
            'user_id' => $user->id,
        ]);
        
        Bookmark::factory()->create([
            'title' => 'Bookmark from month 1',
            'updated_at' => now()->month(1),
            'user_id' => $user->id,
        ]);

        // Endpoint: "bookmarks?filter[month]=3"
        $url = route('api.v1.bookmarks.index', [
            'filter' => [
                'month' => '3'
            ]
        ]);
        
        $this->getJson($url)
            ->assertJsonCount(2, 'data')
            ->assertSee('Bookmark from month 3')
            ->assertSee('Another Bookmark from month 3')
            ->assertDontSee('Bookmark from month 1');
    }
    
    /** @test */
    public function cannot_filter_bookmarks_by_unknwon_filters(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Bookmark::factory()->count(2)->create([
            'user_id' => $user->id
        ]);

        // Endpoint: "bookmarks?filter[unknown]=filter"
        $url = route('api.v1.bookmarks.index', [
            'filter' => [
                'unknown' => 'filter'
            ]
        ]);
        
        $this->getJson($url)->assertStatus(400);
    }
}
