<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddBookmarkController extends Controller
{
    public function addBookmark(Bookmark $bookmark, Collection $collection){
        DB::table('bookmark_collection')->insert([
            'bookmark_id' => $bookmark->id,
            'collection_id' => $collection->id,
        ]);
    }
}
