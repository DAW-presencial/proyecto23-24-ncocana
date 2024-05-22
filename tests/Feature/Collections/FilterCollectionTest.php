<?php

namespace Tests\Feature\Collections;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FilterCollectionTest extends TestCase
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
    public function can_filter_collections_by_name(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Collection::factory()->create([
            'name' => 'First collection',
            'user_id' => $user->id,
        ]);
        
        Collection::factory()->create([
            'name' => 'Other collection',
            'user_id' => $user->id,
        ]);

        // Endpoint: "collections?filter[name]=first"
        $url = route('api.v1.collections.index', [
            'filter' => [
                'name' => 'first'
            ]
        ]);
        // dd($this->getJson($url));

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('First collection')
            ->assertDontSee('Other collection');
    }
    
    /** @test */
    public function can_filter_collections_by_description(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Collection::factory()->create([
            'description' => 'My kingdom come undone',
            'user_id' => $user->id,
        ]);
        
        Collection::factory()->create([
            'description' => 'Other collection',
            'user_id' => $user->id,
        ]);

        // Endpoint: "collections?filter[description]=kingdom"
        $url = route('api.v1.collections.index', [
            'filter' => [
                'description' => 'kingdom'
            ]
        ]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('My kingdom come undone')
            ->assertDontSee('Other collection');
    }
    
    /** @test */
    public function can_filter_collections_by_year(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Collection::factory()->create([
            'name' => 'Collection from 2021',
            'updated_at' => now()->year(2021),
            'user_id' => $user->id,
        ]);
        
        Collection::factory()->create([
            'name' => 'Collection from 2022',
            'updated_at' => now()->year(2022),
            'user_id' => $user->id,
        ]);

        // Endpoint: "collections?filter[year]=2021"
        $url = route('api.v1.collections.index', [
            'filter' => [
                'yearUpdate' => '2021'
            ]
        ]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Collection from 2021')
            ->assertDontSee('Collection from 2022');
    }
    
    /** @test */
    public function can_filter_collections_by_month(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Collection::factory()->create([
            'name' => 'Collection from month 3',
            'updated_at' => now()->month(3),
            'user_id' => $user->id,
        ]);
        
        Collection::factory()->create([
            'name' => 'Another Collection from month 3',
            'updated_at' => now()->month(3),
            'user_id' => $user->id,
        ]);
        
        Collection::factory()->create([
            'name' => 'Collection from month 1',
            'updated_at' => now()->month(1),
            'user_id' => $user->id,
        ]);

        // Endpoint: "collections?filter[month]=3"
        $url = route('api.v1.collections.index', [
            'filter' => [
                'monthUpdate' => '3'
            ]
        ]);
        
        $this->getJson($url)
            ->assertJsonCount(2, 'data')
            ->assertSee('Collection from month 3')
            ->assertSee('Another Collection from month 3')
            ->assertDontSee('Collection from month 1');
    }
    
    /** @test */
    public function cannot_filter_collections_by_unknwon_filters(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Collection::factory()->count(2)->create([
            'user_id' => $user->id
        ]);

        // Endpoint: "collections?filter[unknown]=filter"
        $url = route('api.v1.collections.index', [
            'filter' => [
                'unknown' => 'filter'
            ]
        ]);
        
        $this->getJson($url)->assertStatus(400);
    }
}
