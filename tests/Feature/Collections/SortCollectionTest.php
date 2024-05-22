<?php

namespace Tests\Feature\Collections;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SortCollectionTest extends TestCase
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
    public function can_sort_collections_by_name(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'B title'
        ]);
        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'D title'
        ]);
        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'A title'
        ]);
        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'C title'
        ]);

        // Query Sort = "/collections?sort=name"
        $url = route('api.v1.collections.index', ['sort' => 'name']);
        
        $this->getJson($url)->assertSeeInOrder([
            'A title',
            'B title',
            'C title',
            'D title',
        ]);
        
        $this->assertDatabaseCount('collections', 4);
    }

    /** @test */
    public function can_sort_collections_by_name_descending(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'B title'
        ]);
        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'D title'
        ]);
        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'A title'
        ]);
        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'C title'
        ]);

        // Query Sort = "/collections?sort=-name"
        $url = route('api.v1.collections.index', ['sort' => '-name']);
        
        $this->getJson($url)->assertSeeInOrder([
            'D title',
            'C title',
            'B title',
            'A title',
        ]);
        
        $this->assertDatabaseCount('collections', 4);
    }

    /** @test */
    public function can_sort_collections_by_created_at_descending(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        $collection1 = Collection::factory()->create([
            'created_at' => now()->year(2023),
            'user_id' => $user->id,
        ]);
        $collection2 = Collection::factory()->create([
            'created_at' => now()->year(2021),
            'user_id' => $user->id,
        ]);
        $collection3 = Collection::factory()->create([
            'created_at' => now()->month(3),
            'user_id' => $user->id,
        ]);
        $collection4 = Collection::factory()->create([
            'created_at' => now()->month(1),
            'user_id' => $user->id,
        ]);

        // Query Sort = "/collections?sort=-created_at"
        $url = route('api.v1.collections.index', ['sort' => '-created_at']);
    
        $this->getJson($url)->assertSeeInOrder([
            $collection3->title,
            $collection4->title,
            $collection1->title,
            $collection2->title,
        ]);
        
        $this->assertDatabaseCount('collections', 4);
    }

    /** @test */
    public function can_sort_collections_by_name_and_created_at(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'B title',
            'created_at' => now()->year(2022),
        ]);
        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'A title',
            'created_at' => now()->year(2023),
        ]);
        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'D title',
            'created_at' => now()->year(2024),
        ]);
        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'E title',
            'created_at' => now()->year(2022),
        ]);
        Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'C title',
            'created_at' => now()->year(2023),
        ]);

        // Query Sort = "/collections?sort=created_at,-name"
        $url = route('api.v1.collections.index', ['sort' => 'created_at,-name']);
        
        $this->getJson($url)->assertSeeInOrder([
            'E title',
            'B title',
            'C title',
            'A title',
            'D title',
        ]);
        
        $this->assertDatabaseCount('collections', 5);
    }
    
    /** @test */
    public function cannot_sort_articles_by_unknown_fields(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        Collection::factory(3)->create([
            'user_id' => $user->id
        ]);

        // Query Sort = "/collections?sort=unknown"
        $url = route('api.v1.collections.index', ['sort' => 'unknown']);
        
        $this->getJson($url)->assertStatus(400);
        $this->assertDatabaseCount('collections', 3);
    }
}
