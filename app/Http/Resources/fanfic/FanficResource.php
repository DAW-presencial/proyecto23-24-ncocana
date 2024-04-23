<?php

namespace App\Http\Resources\fanfic;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FanficResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'fanfics',
            'id' => (string) $this->resource->getRouteKey(),
            'attributes' => [
                'title' => $this->resource->title,
                'author' => $this->resource->author,
                'fandom' => $this->resource->fandom,
                'relationships' => $this->resource->relationships,
                'language' => $this->resource->language,
                'words' => $this->resource->words,
                'read_chapters' => $this->resource->read_chapters,
                'total_chapters' => $this->resource->total_chapters,
                'synopsis' => $this->resource->synopsis,
                'notes' => $this->resource->notes,

            ],
            'links' =>[
                'self' => route('api.v1.fanfics.show', $this->resource)

            ]
         ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->withHeaders([
            'Location' => route('api.v1.fanfics.show', $this->resource)
        ]);
    }
}
