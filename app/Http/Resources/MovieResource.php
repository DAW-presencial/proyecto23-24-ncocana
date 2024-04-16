<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'movies',
            'id' => (string) $this->resource->getRouteKey(),
            'attributes' => [
                'title' => $this->resource->title,
                'director' => $this->resource->director,
                'actors' => $this->resource->actors,
                'release_date' => $this->resource->release_date,
                'currently_at' => $this->resource->currently_at,
                'synopsis' => $this->resource->synopsis,
                'notes' => $this->resource->notes

            ],
            'links' =>[
                'self' => route('api.v1.movies.show', $this->resource)

            ]
         ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->withHeaders([
            'Location' => route('api.v1.movies.show', $this->resource)
        ]);
    }
}

