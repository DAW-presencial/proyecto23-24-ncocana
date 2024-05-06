<?php

namespace Tests\Feature\Bookmarks;

use App\Models\Bookmark;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaginateBookmarkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_paginate_bookmarks(): void
    {
        $bookmarks = Bookmark::factory(6)->create();

        // Route: "/api/v1/bookmarks?page[size]=2&page[number]=2"
        $url = route('api.v1.bookmarks.index', [
            'page' => [
                'size' => 2,
                'number' => 2
            ]
        ]);
        // dd(urldecode($url));

        $response = $this->getJson($url);

        // Con "size" 2 y "number" 2, se obtienen los bookmarks 2 y 3.
        $response->assertSee([
            $bookmarks[2]->title,
            $bookmarks[3]->title,
        ]);

        // No se deben ver el resto de bookmarks.
        $response->assertDontSee([
            $bookmarks[0]->title,
            $bookmarks[1]->title,
            $bookmarks[4]->title,
            $bookmarks[5]->title,
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
    public function can_paginate_sorted_bookmarks(): void
    {
        Bookmark::factory()->ofType('App\Models\Movie')->create();
        Bookmark::factory()->ofType('App\Models\Fanfic')->create();
        Bookmark::factory()->ofType('App\Models\Book')->create();
        Bookmark::factory()->ofType('App\Models\Series')->create();

        // Route: "/api/v1/bookmarks?sort=bookmarkable_type&page[size]=1&page[number]=2"
        $url = route('api.v1.bookmarks.index', [
            'sort' => 'bookmarkable_type',
            'page' => [
                'size' => 1,
                'number' => 2
            ]
        ]);

        $response = $this->getJson($url);

        // Ordena la respuesta por el "bookmarkable_type" y devuelve la página 2 con tamaño 1.
        // Resultado de la respuesta páginada: 'App\\\Models\\\Movie'.
        $response->assertSee([
            'App\\\Models\\\Fanfic',
        ]);

        // No devolverá el resto de bookmarks.
        $response->assertDontSee([
            'App\\\Models\\\Book',
            'App\\\Models\\\Movie',
            'App\\\Models\\\Series',
        ]);

        $firstLink = urldecode($response->json('links.first'));
        $lastLink = urldecode($response->json('links.last'));
        $prevLink = urldecode($response->json('links.prev'));
        $nextLink = urldecode($response->json('links.next'));

        $this->assertStringContainsString('sort=bookmarkable_type', $firstLink);
        $this->assertStringContainsString('sort=bookmarkable_type', $lastLink);
        $this->assertStringContainsString('sort=bookmarkable_type', $prevLink);
        $this->assertStringContainsString('sort=bookmarkable_type', $nextLink);
    }

    /** @test */
    public function can_paginate_filtered_bookmarks(): void
    {
        Bookmark::factory()->ofType('App\Models\Movie')->create();
        Bookmark::factory()->ofType('App\Models\Fanfic')->create();
        Bookmark::factory()->ofType('App\Models\Fanfic')->create();
        Bookmark::factory()->ofType('App\Models\Fanfic')->create();
        Bookmark::factory()->ofType('App\Models\Book')->create();
        Bookmark::factory()->ofType('App\Models\Series')->create();

        // Route: "/api/v1/bookmarks?filter[bookmarkable_type]=Fanfic&page[size]=1&page[number]=2"
        $url = route('api.v1.bookmarks.index', [
            'filter[bookmarkable_type]' => 'Fanfic',
            'page' => [
                'size' => 1,
                'number' => 2
            ]
        ]);

        $response = $this->getJson($url);

        $firstLink = urldecode($response->json('links.first'));
        $lastLink = urldecode($response->json('links.last'));
        $prevLink = urldecode($response->json('links.prev'));
        $nextLink = urldecode($response->json('links.next'));

        $this->assertStringContainsString('filter[bookmarkable_type]=Fanfic', $firstLink);
        $this->assertStringContainsString('filter[bookmarkable_type]=Fanfic', $lastLink);
        $this->assertStringContainsString('filter[bookmarkable_type]=Fanfic', $prevLink);
        $this->assertStringContainsString('filter[bookmarkable_type]=Fanfic', $nextLink);
    }
}
