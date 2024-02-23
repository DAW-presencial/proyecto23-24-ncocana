<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fanfic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'fandom',
        'relationships',
        'language',
        'words',
        'read_chapters',
        'total_chapters',
        'synopsis',
        'notes',
    ];

    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }
}
