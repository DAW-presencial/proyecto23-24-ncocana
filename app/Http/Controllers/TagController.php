<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Collection;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function store($model, $tags)
    {
        if ($model instanceof Bookmark || $model instanceof Collection) {
            if (is_string($tags)) {
                $model->attachTag($tags);
            } elseif (is_array($tags)) {
                $model->attachTags($tags);
            } else {
                // Handle other cases or throw an exception
                throw new \InvalidArgumentException('The parameter $tags should be either a string or an array.');
            }
        } else {
            // Handle other cases or throw an exception
            throw new \InvalidArgumentException('The parameter $model should be either a Bookmark object or a Collection object.');
        }
    }
}
