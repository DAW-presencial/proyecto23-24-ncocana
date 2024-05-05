<?php

namespace App\Http\Resources\Series;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'series',
            'id' => (string) $this->resource->getRouteKey(),
            'attributes' => [
                'title' => $this->resource->title,
                'actors' => $this->resource->actors,
                'num_seasons' => $this->resource->num_seasons,
                'num_episodes' => $this->resource->num_episodes,
                'currently_at' => $this->resource->currently_at,
                'synopsis' => $this->resource->synopsis,
                'notes' => $this->resource->notes

            ],
            'links' =>[
                'self' => route('api.v1.series.show', $this->resource)

            ]
         ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->withHeaders([
            'Location' => route('api.v1.series.show', $this->resource)
        ]);
    }
}
