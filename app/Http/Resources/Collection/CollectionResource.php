<?php

namespace App\Http\Resources\Collection;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'collections',
            'id' => (string) $this->resource->getRouteKey(),
            'attributes' => [
                'user_id' => $this->resource->user_id,
                'name' => $this->resource->name,
                'description' => $this->resource->description,
                "bookmarks" => $this->resource->bookmarks,
                'tags' => $this->extractTagInformation($this->resource->tags),
            ],
            'links' =>[
                'self' => route('api.v1.collections.show', $this->resource)

            ]
         ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->withHeaders([
            'Location' => route('api.v1.collections.show', $this->resource)
        ]);
    }

    private function extractTagInformation($tags)
    {
        return $tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'pivot' => [
                    'taggable_type' => $tag->pivot->taggable_type,
                    'taggable_id' => $tag->pivot->taggable_id,
                    'tag_id' => $tag->pivot->tag_id,
                ]
            ];
        });
    }
}
