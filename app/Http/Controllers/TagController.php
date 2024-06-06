<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Collection;

class TagController extends Controller
{
    // Define the maximum number of tags allowed
    const MAX_TAGS = 10;

    public function index($model, $tags)
    {
        if ($model instanceof Bookmark || $model instanceof Collection) {
            // Separate the tags into an array
            $tagsArray = explode(',', $tags);

            // Filter bookmarks by tags
            $modelCollection = $model::withAllTags($tagsArray);
            // dd($modelCollection->get());

            return $modelCollection;
        } else {
            // Handle other cases or throw an exception
            throw new \InvalidArgumentException('The parameter $model should be either a Bookmark object or a Collection object.');
        }
    }

    public function store($model, $tags)
    {
        if ($model instanceof Bookmark || $model instanceof Collection) {
            $currentTagsCount = $model->tags()->count();

            if (is_string($tags)) {
                $newTagsCount = 1;
            } elseif (is_array($tags)) {
                $newTagsCount = count($tags);
            } else {
                throw new \InvalidArgumentException('The parameter $tags should be either a string or an array.');
            }

            if ($currentTagsCount + $newTagsCount > self::MAX_TAGS) {
                return response()->json(['message' => 'Cannot add more than ' . self::MAX_TAGS . ' tags.'], 400);
            }

            if (is_string($tags)) {
                $model->attachTag($tags);
            } elseif (is_array($tags)) {
                $model->attachTags($tags);
            }
        } else {
            throw new \InvalidArgumentException('The parameter $model should be either a Bookmark object or a Collection object.');
        }
    }

    public function update($model, $tags)
    {
        if ($model instanceof Bookmark || $model instanceof Collection) {
            if (is_string($tags)) {
                $tagsArray = [$tags];
            } elseif (is_array($tags)) {
                $tagsArray = $tags;
            } else {
                throw new \InvalidArgumentException('The parameter $tags should be either a string or an array.');
            }

            if (count($tagsArray) > self::MAX_TAGS) {
                return response()->json(['message' => 'Cannot have more than ' . self::MAX_TAGS . ' tags.'], 400);
            }

            $model->syncTags($tagsArray);
        } else {
            throw new \InvalidArgumentException('The parameter $model should be either a Bookmark object or a Collection object.');
        }
    }
}
