<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Tags\HasTags;

class Bookmark extends Model
{
    use HasFactory;
    use HasTags;

    public function bookmarkable()
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function collections() : BelongsToMany
    {
        return $this->BelongsToMany(Collection::class);
    }
}
