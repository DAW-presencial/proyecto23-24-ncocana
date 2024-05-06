<?php

namespace App\Http\Controllers;

use App\Http\Resources\Bookmark\BookmarkCollection;
use App\Http\Resources\Bookmark\BookmarkResource;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')
            ->only([
                'store',
                'update',
                'destroy'
            ]);
    }

    public function index()
    {
        // This returns a query builder instance
        $bookmarks = Bookmark::query()->with('bookmarkable');
    
        $bookmarks = $bookmarks->allowedSorts(['bookmarkable_type', 'user_id', 'title', 'created_at', 'updated_at'])
                               ->allowedFilters(['bookmarkable_type', 'user_id', 'title', 'synopsis', 'notes'])
                               ->jsonPaginate();
    
        return BookmarkCollection::make($bookmarks);
    }

    public function store()
    {
        
    }
  
    public function show(Bookmark $bookmark)
    {
        // Eager load the bookmarkable entity
        $bookmark->load('bookmarkable');

        return BookmarkResource::make($bookmark);
    }

    public function update() { 
        
    }

    public function destroy(Bookmark $bookmark)
    {
        // Get the bookmarkable type
        $bookmarkableType = $bookmark->bookmarkable_type;

        // Delete the bookmark
        $bookmark->delete();

        // Determine the controller class based on the bookmarkable type
        $controllerClass = 'App\Http\Controllers\\' . class_basename($bookmarkableType) . 'Controller';

        // Check if the controller class exists
        if (class_exists($controllerClass)) {
            // Call the destroy method of the controller
            app($controllerClass)->destroy($bookmark->bookmarkable);
        }

        // Respond with a success message
        return response()->json(['message' => 'Bookmark deleted successfully']);
    }
}
