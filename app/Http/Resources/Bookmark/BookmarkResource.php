<?php

namespace App\Http\Resources\Bookmark;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'bookmarks',
            'id' => (string) $this->resource->getRouteKey(),
            'attributes' => [
                'user_id' => $this->resource->user_id,
                'bookmarkable_type' => $this->resource->bookmarkable_type,
                'bookmarkable_id' => $this->resource->bookmarkable_id,
                "bookmarkable" => $this->resource->bookmarkable
            ],
            'links' =>[
                'self' => route('api.v1.bookmarks.show', $this->resource)
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
