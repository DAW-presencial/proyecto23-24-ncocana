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
            'store',
            'update',
            'destroy'
        ]);
        
    }


    public function index()
    {
        $movies = Movie::query()     // Se usan mixins para extender builder y aplicar parámetros en la búsqueda
            ->allowedSorts(['director', 'actors', 'release_date', 'currently_at'])
            ->allowedFilters(['director', 'actors', 'release_date', 'currently_at'])
            ->jsonPaginate();
        
        return MovieResource::collection($movies);
        //Se utiliza un resource para la adhesión a la especificación ApiJson de la respuesta
    }


    public function store(MovieRequest $request) // Se utiliza un form request para la validación
    {
        $movie= Movie::create([
            'director' => $request->director,
            'actors' => $request->actors,
            'release_date' => $request->release_date,
            'currently_at' => $request->currently_at,
        ]);

        $user = Auth::id();  //Recoge el id del usuario autenticado

        $movie->bookmarks()->create([
            'user_id' => $user,
            'title' => $request->title,
            'synopsis' => $request->synopsis,
            'notes' => $request->notes,
        ]);
        //Crea un bookmark relacioando al libro y al usuario autenticado

        MovieResource::make($movie);
    }


    public function show(Movie $movie)
    {
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
        
        MovieResource::make($movie);
    }

   
    public function destroy(Movie $movie)
    {
        $movie->delete();
        return response()->json([
            "succes" =>"La película ".$movie->id." ha sido borrada con éxito"
        ]);
    }
}
