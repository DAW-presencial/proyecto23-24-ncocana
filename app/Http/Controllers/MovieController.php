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

        // Create the bookmark associated with the series and user
        $movie->bookmarks()->create([
            'user_id' => $user,
            'title' => $validatedData['title'],
            'synopsis' => $validatedData['synopsis'],
            'notes' => $validatedData['notes'],
        ]);

        // Return the series resource
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

    public function update(MovieUpdate $request, Movie $movie) { 
        //Se utiliza un formRequest especial para la validación que no tenga los campos title y director requeridos
        $movie->fill([
            'director' => $request->input('director', $movie->director),
            'actors' => $request->input('actors', $movie->actors),
            'release_date' => $request->input('release_date', $movie->release_date),
            'currently_at' => $request->input('currently_at', $movie->currently_at),
        ])->save();
        // Con Fill() y save() no hace falta meter todos los atributos en la petición sólo los que modifiquemos
        // Con el segundo parámetro de input() nos aseguramos que si no pasamos un atributo coja los del libro por defecto
        
        $movie->bookmarks()->update([
            'title' => $request->title,
            'synopsis' => $request->synopsis,
            'notes' => $request->notes,
        ]);
        
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
