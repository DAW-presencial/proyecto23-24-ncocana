<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Tags\HasTags;

class Collection extends Model
{
    use HasFactory, HasTags;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', //Se añade el campo user_id para la relación con el usuario
        'name',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(Bookmark::class, 'bookmark_collection');
    }

    public function scopeYear(Builder $query, $year)
    {
        $query->whereYear('updated_at', $year);
    }

    public function scopeMonth(Builder $query, $month)
    {
        $query->whereMonth('updated_at', $month);
    }
}
