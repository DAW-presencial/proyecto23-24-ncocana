<?php

namespace Tests\Feature\Collections;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PaginateCollectionTest extends TestCase
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
    public function can_paginate_collections(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        $collections = Collection::factory(6)->create([
            'user_id' => $user->id
        ]);

        // Route: "/api/v1/collections?page[size]=2&page[number]=2"
        $url = route('api.v1.collections.index', [
            'page' => [
                'size' => 2,
                'number' => 2
            ]
        ]);
        // dd(urldecode($url));

        $response = $this->getJson($url);

        // Con "size" 2 y "number" 2, se obtienen los collections 2 y 3.
        $response->assertSee([
            $collections[2]->name,
            $collections[3]->name,
        ]);

        // No se deben ver el resto de collections.
        $response->assertDontSee([
            $collections[0]->name,
            $collections[1]->name,
            $collections[4]->name,
            $collections[5]->name,
        ]);

        // Las cabeceras las añade el trait "MakesJsonApiRequests" en "/tests".
        // dd($response);
        $response->assertJsonStructure([
            'links' => ['first', 'last', 'prev', 'next']
        ]);

        $firstLink = urldecode($response->json('links.first'));
        $lastLink = urldecode($response->json('links.last'));
        $prevLink = urldecode($response->json('links.prev'));
        $nextLink = urldecode($response->json('links.next'));

        // dd($lastLink);
        $this->assertStringContainsString('page[size]=2', $firstLink);
        $this->assertStringContainsString('page[number]=1', $firstLink);

        $this->assertStringContainsString('page[size]=2', $lastLink);
        $this->assertStringContainsString('page[number]=3', $lastLink);

        $this->assertStringContainsString('page[size]=2', $prevLink);
        $this->assertStringContainsString('page[number]=1', $prevLink);

        $this->assertStringContainsString('page[size]=2', $nextLink);
        $this->assertStringContainsString('page[number]=3', $nextLink);
    }

    /** @test */
    public function can_paginate_sorted_collections(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        $collection1 = Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'B title'
        ]);
        $collection2 = Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'D title'
        ]);
        $collection3 = Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'A title'
        ]);
        $collection4 = Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'C title'
        ]);

        // Route: "/api/v1/collections?sort=name&page[size]=1&page[number]=2"
        $url = route('api.v1.collections.index', [
            'sort' => 'name',
            'page' => [
                'size' => 1,
                'number' => 2
            ]
        ]);

        $response = $this->getJson($url);

        // Ordena la respuesta por el "name" y devuelve la página 2 con tamaño 1.
        // Resultado de la respuesta páginada: 'B title'.
        $response->assertSee([
            $collection1->name
        ]);

        // No devolverá el resto de collections.
        $response->assertDontSee([
            $collection2->name,
            $collection3->name,
            $collection4->name,
        ]);

        $firstLink = urldecode($response->json('links.first'));
        $lastLink = urldecode($response->json('links.last'));
        $prevLink = urldecode($response->json('links.prev'));
        $nextLink = urldecode($response->json('links.next'));

        $this->assertStringContainsString('sort=name', $firstLink);
        $this->assertStringContainsString('sort=name', $lastLink);
        $this->assertStringContainsString('sort=name', $prevLink);
        $this->assertStringContainsString('sort=name', $nextLink);
    }

    /** @test */
    public function can_paginate_filtered_collections(): void
    {
        // Retrieve the authenticated user
        $user = User::first();

        $collection1 = Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'Masquerade'
        ]);
        $collection2 = Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'Fluff'
        ]);
        $collection3 = Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'Marvel'
        ]);
        $collection4 = Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'Tooth-Rotting Fluff'
        ]);
        $collection5 = Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'Live to die'
        ]);
        $collection6 = Collection::factory()->create([
            'user_id' => $user->id,
            'name' => 'Fluff for my soul'
        ]);

        // Route: "/api/v1/collections?filter[name]=fluff&page[size]=1&page[number]=2"
        $url = route('api.v1.collections.index', [
            'filter[name]' => 'fluff',
            'page' => [
                'size' => 1,
                'number' => 2
            ]
        ]);

        $response = $this->getJson($url);
        
        $response->assertJsonCount(1, 'data')
            ->assertSee([
                $collection2->name,
                $collection4->name,
            ])
            ->assertDontSee([
                $collection1->name,
                $collection3->name,
                $collection5->name,
                $collection6->name,
            ]);

        $firstLink = urldecode($response->json('links.first'));
        $lastLink = urldecode($response->json('links.last'));
        $prevLink = urldecode($response->json('links.prev'));
        $nextLink = urldecode($response->json('links.next'));

        $this->assertStringContainsString('filter[name]=fluff', $firstLink);
        $this->assertStringContainsString('filter[name]=fluff', $lastLink);
        $this->assertStringContainsString('filter[name]=fluff', $prevLink);
        $this->assertStringContainsString('filter[name]=fluff', $nextLink);
    }
}
