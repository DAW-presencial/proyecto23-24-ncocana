<?php

namespace Tests\Feature\Bookmarks;

use App\Models\Bookmark;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilterBookmarkTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function can_filter_bookmarks_by_title(): void
    {
        Bookmark::factory()->create([
            'title' => 'My kingdom come undone'
        ]);
        
        Bookmark::factory()->create([
            'title' => 'Other bookmark'
        ]);

        // Endpoint: "bookmarks?filter[title]=kingdom"
        $url = route('api.v1.bookmarks.index', [
            'filter' => [
                'title' => 'kingdom'
            ]
        ]);
        // dd($this->getJson($url));

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('My kingdom come undone')
            ->assertDontSee('Other bookmark');
    }
    
    /** @test */
    public function can_filter_bookmarks_by_synopsis(): void
    {
        Bookmark::factory()->create([
            'synopsis' => 'Go to Liyue and build a false identity as a backup plan, they said. It\'d be easy, they said. Pantalone would like to express that everything the Fatui Harbingers say is bullshit. Otherwise known as the "pantalone is baizhu and i will die on this hill" story'
        ]);
        
        Bookmark::factory()->create([
            'synopsis' => 'Other bookmark'
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
    public function can_filter_bookmarks_by_year(): void
    {
        Bookmark::factory()->create([
            'title' => 'Bookmark from 2021',
            'created_at' => now()->year(2021)
        ]);
        
        Bookmark::factory()->create([
            'title' => 'Bookmark from 2022',
            'created_at' => now()->year(2022)
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
        Bookmark::factory()->create([
            'title' => 'Bookmark from month 3',
            'created_at' => now()->month(3)
        ]);
        
        Bookmark::factory()->create([
            'title' => 'Another Bookmark from month 3',
            'created_at' => now()->month(3)
        ]);
        
        Bookmark::factory()->create([
            'title' => 'Bookmark from month 1',
            'created_at' => now()->month(1)
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
        Bookmark::factory()->count(2)->create();

        // Endpoint: "bookmarks?filter[unknown]=filter"
        $url = route('api.v1.bookmarks.index', [
            'filter' => [
                'unknown' => 'filter'
            ]
        ]);
        
        $this->getJson($url)->assertStatus(400);
    }
}
