<?php

namespace App\Http\Controllers;

use App\Http\Requests\Series\SeriesRequest;
use App\Http\Requests\Series\SeriesUpdate;
use App\Http\Resources\Series\SeriesResource;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeriesController extends Controller
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

        $series = Series::query()
            ->where('user_id', $userId)
            ->allowedSorts(['actors', 'num_seasons', 'num_episodes', 'currently_at'])
            ->allowedFilters(['actors', 'num_seasons', 'num_episodes', 'currently_at'])
            ->jsonPaginate();
        
        return SeriesResource::collection($series);
    }

    public function store(SeriesRequest $request)
    {
        // Get validated input data directly
        $validatedData = $request->input();
        
        // Create the series using validated data
        $series = Series::create([
            'actors' => $validatedData['bookmarkable']['actors'],
            'num_seasons' => $validatedData['bookmarkable']['num_seasons'],
            'num_episodes' => $validatedData['bookmarkable']['num_episodes'],
            'currently_at' => $validatedData['bookmarkable']['currently_at'],
        ]);

        // Get the authenticated user's ID
        $user = Auth::id();

        // Create the bookmark associated with the series and user
        $series->bookmarks()->create([
            'user_id' => $user,
            'title' => $validatedData['title'],
            'synopsis' => $validatedData['synopsis'],
            'notes' => $validatedData['notes'],
        ]);

        // Return the series resource
        return SeriesResource::make($series);
    }

    public function show(Series $series)
    {
        // Get the currently authenticated user's ID
        $userId = Auth::id();

        // Check if the series belongs to the current user
        if ($series->user_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return SeriesResource::make($series);
    }

    public function update(Series $series, SeriesRequest $request)
    {
        // Get validated input data directly
        $validatedData = $request->input();
        
        // Update the series using validated data
        // If some field is not in the request, use the existing data from the model instead
        $series->update([
            'actors' => $validatedData['bookmarkable']['actors'] ?? $series->actors,
            'num_seasons' => $validatedData['bookmarkable']['num_seasons'] ?? $series->num_seasons,
            'num_episodes' => $validatedData['bookmarkable']['num_episodes'] ?? $series->num_episodes,
            'currently_at' => $validatedData['bookmarkable']['currently_at'] ?? $series->currently_at,
        ]);
        
        // Get the first bookmark associated with the series
        $bookmark = $series->bookmarks()->first();
        // Update the bookmark associated with the series and user
        if ($bookmark) {
            $bookmark->update([
                'title' => $validatedData['title'] ?? $bookmark->title,
                'synopsis' => $validatedData['synopsis'] ?? $bookmark->synopsis,
                'notes' => $validatedData['notes'] ?? $bookmark->notes,
            ]);
        }
        
        // Return the series resource
        return SeriesResource::make($series);
    }

    public function destroy(Series $series)
    {
        $series->delete();
        
        return response()->json([
            "message" => 'The series "' . $series->id . '" has been successfully deleted.'
        ]);
    }
}
