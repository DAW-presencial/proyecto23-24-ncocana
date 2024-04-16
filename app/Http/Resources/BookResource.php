<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'books',
            'id' => (string) $this->resource->getRouteKey(),
            'attributes' => [
                'title' => $this->resource->title,
                'author' => $this->resource->author,
                'language' => $this->resource->language,
                'read_pages' => $this->resource->read_pages,
                'total_pages' => $this->resource->total_pages,
                'synopsis' => $this->resource->synopsis,
                'notes' => $this->resource->notes

            ],
            'links' =>[
                'self' => route('api.v1.books.show', $this->resource)

            ]
         ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->withHeaders([
            'Location' => route('api.v1.books.show', $this->resource)
        ]);
    }
}
