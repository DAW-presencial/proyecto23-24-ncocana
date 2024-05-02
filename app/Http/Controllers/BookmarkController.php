<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index()
    {
        // This returns a query builder instance
        $bookmarks = Bookmark::query()->with('bookmarkable');
    
        $bookmarks = $bookmarks->allowedSorts(['bookmarkable_type', 'user_id', 'created_at', 'updated_at'])
                               ->allowedFilters(['bookmarkable_type', 'user_id'])
                               ->jsonPaginate();
    
        return response()->json($bookmarks);

    }

    public function store()
    {
        
    }
  
    public function show()
    {
       
    }

    public function update() { 
        
    }

    public function destroy()
    {
        
    }
}
