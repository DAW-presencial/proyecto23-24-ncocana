<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bookmark\BookmarkRequest;
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

        // Check if 'tags' parameter exists
        $tags = request('tags');

        if ($tags) {
            // Call the index method of TagController
            $bookmarks = app(TagController::class)->index(new Bookmark(), $tags);
            // Query tagged bookmarks for the current user with fields 'bookmarkable' and 'tags'
            $bookmarks = $bookmarks->where('user_id', Auth::id())->with(['bookmarkable', 'tags']);
        } else {
            // Query bookmarks for the current user with fields 'bookmarkable' and 'tags'
            $bookmarks = Bookmark::query()->where('user_id', $userId)->with(['bookmarkable', 'tags']);
        }

        // Order bookmarks by 'updated_at' in descending order
        // $bookmarks = $bookmarks->orderBy('updated_at', 'desc');

        $bookmarks = $bookmarks->allowedSorts(['bookmarkable_type', 'title', 'created_at', 'updated_at'])
            ->allowedFilters(['bookmarkable_type', 'title', 'synopsis', 'notes', 'month', 'year'])
            ->jsonPaginate();

        return BookmarkCollection::make($bookmarks);
    }

    public function store(BookmarkRequest $request)
    {
        // Get the request data
        $requestData = $request->validated();

        // Extract the attributes from the request data
        $attributes = $requestData['data']['attributes'];

        // Get the bookmarkable type
        $bookmarkableType = $attributes['bookmarkable_type'];

        // Determine the controller class based on the bookmarkable type
        $controllerClass = 'App\Http\Controllers\\' . class_basename($bookmarkableType) . 'Controller';
        $requestClass = 'App\Http\Requests\\' . class_basename($bookmarkableType) . '\\' . class_basename($bookmarkableType) . 'Request';

        // Check if the controller class exists
        if (!class_exists($controllerClass)) {
            return response()->json(['message' => 'Controller class not found'], 500);
        }
        // Check if the controller class exists
        if (!class_exists($requestClass)) {
            return response()->json(['message' => 'Request class not found'], 500);
        }

        // Create an instance of the appropriate controller class based on the bookmarkable type
        $controllerInstance = app($controllerClass);
        // Create an instance of the appropriate request class based on the bookmarkable type
        $bookmarkRequest = new $requestClass($attributes);
        // Call the store method of the controller with the appropriate request
        $resource = $controllerInstance->store($bookmarkRequest);
        // dd($resource);

        // Find the bookmark associated with the created resource
        $bookmark = Bookmark::where('bookmarkable_type', 'App\Models\\' . $bookmarkableType)
            ->where('bookmarkable_id', $resource->id)
            ->first();

        // Check if the bookmark is found
        if (!$bookmark) {
            return response()->json(['message' => 'Bookmark not found'], 404);
        }
        // dd(BookmarkResource::make($bookmark));

        // Check if 'tags' key exists before accessing it
        if (isset($attributes['tags']) && $attributes['tags']) {
            app(TagController::class)->store($bookmark, $attributes['tags']);
        }

        // Eager load the bookmarkable entity and tags relationship
        $bookmark->load(['bookmarkable', 'tags']);

        // Return the created bookmark resource
        return BookmarkResource::make($bookmark);
    }

    public function show(Bookmark $bookmark)
    {
        // Get the currently authenticated user's ID
        $userId = Auth::id();

        // Check if the bookmark belongs to the current user
        if ($bookmark->user_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Eager load the bookmarkable entity and tags relationship
        $bookmark->load(['bookmarkable', 'tags']);

        return BookmarkResource::make($bookmark);
    }

    public function update(Bookmark $bookmark, BookmarkRequest $request)
    {
        // Get the request data
        $requestData = $request->validated();
        // Extract the attributes from the request data
        $attributes = $requestData['data']['attributes'];

        // Get the bookmarkable type
        $bookmarkableType = $attributes['bookmarkable_type'];

        // Determine the model class based on the bookmarkable type
        $modelClass = 'App\Models\\' . class_basename($bookmarkableType);
        // Check if the model class exists
        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Model class not found'], 500);
        }
        // Create an instance of the appropriate model class based on the bookmarkable type
        $modelInstance = app($modelClass);

        // Find the bookmarkable object associated with the bookmark
        $bookmarkableObject = $modelInstance::where('id', $bookmark->bookmarkable_id)
            ->first();

        // Determine the controller class based on the bookmarkable type
        $controllerClass = 'App\Http\Controllers\\' . class_basename($bookmarkableType) . 'Controller';
        $requestClass = 'App\Http\Requests\\' . class_basename($bookmarkableType) . '\\' . class_basename($bookmarkableType) . 'Request';

        // Check if the controller class exists
        if (!class_exists($controllerClass)) {
            return response()->json(['message' => 'Controller class not found'], 500);
        }
        // Check if the controller class exists
        if (!class_exists($requestClass)) {
            return response()->json(['message' => 'Request class not found'], 500);
        }

        // Create an instance of the appropriate controller class based on the bookmarkable type
        $controllerInstance = app($controllerClass);
        // Create an instance of the appropriate request class based on the bookmarkable type
        $bookmarkRequest = new $requestClass($attributes);
        // Call the update method of the controller with the appropriate request and object
        $resource = $controllerInstance->update($bookmarkableObject, $bookmarkRequest);

        // Find the bookmark associated with the updated resource
        $bookmark = Bookmark::where('bookmarkable_type', 'App\Models\\' . $bookmarkableType)
            ->where('bookmarkable_id', $resource->id)
            ->first();

        // Check if the bookmark is found
        if (!$bookmark) {
            return response()->json(['message' => 'Bookmark not found'], 404);
        }

        // Check if 'tags' key exists before accessing it
        if (isset($attributes['tags']) && $attributes['tags']) {
            app(TagController::class)->update($bookmark, $attributes['tags']);
        }

        // Eager load the bookmarkable entity and tags relationship
        $bookmark->load(['bookmarkable', 'tags']);

        // Return the updated bookmark resource
        return BookmarkResource::make($bookmark);
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
    // {dd($tags);
    //     // Get the currently authenticated user's ID
    //     $userId = Auth::id();

    //     // Query bookmarks for the current user
    //     $bookmarks = Bookmark::query()->where('user_id', $userId)->with(['bookmarkable', 'tags']);

    //     // Check if 'tags' key exists before accessing it
    //     if (isset($tags) && $tags) {
    //         $bookmarks = app(TagController::class)->index($bookmarks, $tags);
    //     }

    //     $bookmarks = $bookmarks->allowedSorts(['bookmarkable_type', 'title', 'created_at', 'updated_at'])
    //                            ->allowedFilters(['bookmarkable_type', 'title', 'synopsis', 'notes', 'month', 'year'])
    //                            ->jsonPaginate();

    //     return BookmarkCollection::make($bookmarks);
    // }
}
