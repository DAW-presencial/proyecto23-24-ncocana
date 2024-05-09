<?php

namespace App\Http\Controllers;

use App\Http\Resources\Bookmark\BookmarkCollection;
use App\Http\Resources\Bookmark\BookmarkResource;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')
            ->only([
                'index',
                'store',
                'show',
                'update',
                'destroy'
            ]);
    }

    public function index()
    {
        // Get the currently authenticated user's ID
        $userId = Auth::id();

        // Query bookmarks for the current user
        $bookmarks = Bookmark::query()->where('user_id', $userId)->with('bookmarkable');
    
        $bookmarks = $bookmarks->allowedSorts(['bookmarkable_type', 'title', 'created_at', 'updated_at'])
                               ->allowedFilters(['bookmarkable_type', 'title', 'synopsis', 'notes', 'month', 'year'])
                               ->jsonPaginate();
    
        return BookmarkCollection::make($bookmarks);
    }

    public function store()
    {
        
    }
  
    public function show(Bookmark $bookmark)
    {
        // Get the currently authenticated user's ID
        $userId = Auth::id();

        // Check if the bookmark belongs to the current user
        if ($bookmark->user_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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
