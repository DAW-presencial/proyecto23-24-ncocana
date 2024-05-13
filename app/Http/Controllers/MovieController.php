<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\MovieRequest;
use App\Http\Requests\Movie\MovieUpdate;
use App\Http\Resources\Movie\MovieResource;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    public function __construct()  //Aplica el Sanctum a los métodos store, update y delete
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

        $movies = Movie::query()
            ->where('user_id', $userId)
            ->allowedSorts(['director', 'actors', 'release_date', 'currently_at'])
            ->allowedFilters(['director', 'actors', 'release_date', 'currently_at'])
            ->jsonPaginate();
        
        return MovieResource::collection($movies);
    }

    public function store(MovieRequest $request)
    {
        // Get validated input data directly
        $validatedData = $request->input();
        
        // Create the movie using validated data
        $movie = Movie::create([
            'director' => $validatedData['bookmarkable']['director'],
            'actors' => $validatedData['bookmarkable']['actors'],
            'release_date' => $validatedData['bookmarkable']['release_date'],
            'currently_at' => $validatedData['bookmarkable']['currently_at'],
        ]);

        // Get the authenticated user's ID
        $user = Auth::id();

        // Create the bookmark associated with the movie and user
        $movie->bookmarks()->create([
            'user_id' => $user,
            'title' => $validatedData['title'],
            'synopsis' => $validatedData['synopsis'],
            'notes' => $validatedData['notes'],
        ]);

        // Return the movie resource
        return MovieResource::make($movie);
    }

    public function show(Movie $movie)
    {
        // Get the currently authenticated user's ID
        $userId = Auth::id();

        // Check if the movie belongs to the current user
        if ($movie->user_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return MovieResource::make($movie);
    }

    public function update(Movie $movie, MovieRequest $request)
    {
        // Get validated input data directly
        $validatedData = $request->input();
        
        // Update the movie using validated data
        // If some field is not in the request, use the existing data from the model instead
        $movie->update([
            'director' => $validatedData['bookmarkable']['director'] ?? $movie->director,
            'actors' => $validatedData['bookmarkable']['actors'] ?? $movie->actors,
            'release_date' => $validatedData['bookmarkable']['release_date'] ?? $movie->release_date,
            'currently_at' => $validatedData['bookmarkable']['currently_at'] ?? $movie->currently_at,
        ]);
        
        // Get the first bookmark associated with the movie
        $bookmark = $movie->bookmarks()->first();
        // Update the bookmark associated with the movie and user
        if ($bookmark) {
            $bookmark->update([
                'title' => $validatedData['title'] ?? $bookmark->title,
                'synopsis' => $validatedData['synopsis'] ?? $bookmark->synopsis,
                'notes' => $validatedData['notes'] ?? $bookmark->notes,
            ]);
        }
        
        // Return the movie resource
        return MovieResource::make($movie);
    }
   
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->json([
            "message" => 'The movie "' . $movie->id . '" has been successfully deleted.'
        ]);
    }
}
