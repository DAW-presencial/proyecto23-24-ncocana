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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'author',
        'fandom',
        'relationships',
        'language',
        'words',
        'chapters_read',
        'total_chapters',
        'synopsis',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'tags' => 'array'
    // ];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function collections() : BelongsToMany
    {
        return $this->BelongsToMany(Collection::class);
    }
}
