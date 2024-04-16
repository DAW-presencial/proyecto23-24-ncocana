<?php

namespace App\Http\Controllers;

use App\Http\Requests\movie\MovieRequest;
use App\Http\Requests\movie\MovieUpdate;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::query()     // Se usan mixins para extender builder y aplicar parámetros en la búsqueda
        ->allowedSorts(['title', 'director'])
        ->jsonPaginate();
        
        return MovieResource::collection($movies);
        //Se utiliza un resource para la adhesión a la especificación ApiJson de la respuesta
    }


    public function store(MovieRequest $request) // Se utiliza un form request para la validación
    {
        $movie= Movie::create([
            'title' => $request->input('data.attributes.title'),
            'director' => $request->input('data.attributes.director'),
            'actors' => $request->input('data.attributes.actors'),
            'release_date' => $request->input('data.attributes.release_date'),
            'currently_at' => $request->input('data.attributes.currently_at'),
            'synopsis' => $request->input('data.attributes.synopsis'),
            'notes' => $request->input('data.attributes.notes'),
        ]);

        MovieResource::make($movie);
    }


    public function show(Movie $movie)
    {
       return MovieResource::make($movie);
    }


    public function update(MovieUpdate $request, Movie $movie) { 
        //Se utiliza un formRequest especial para la validación que no tenga los campos title y director requeridos
            $movie->fill([
                'title' => $request->input('data.attributes.title',$movie->title ),
                'director' => $request->input('data.attributes.director', $movie->director),
                'actors' => $request->input('data.attributes.actors', $movie->actors),
                'release_date' => $request->input('data.attributes.release_date', $movie->release_date),
                'currently_at' => $request->input('data.attributes.currently_at', $movie->currently_at),
                'synopsis' => $request->input('data.attributes.synopsis', $movie->synopsis),
                'notes' => $request->input('data.attributes.notes', $movie->notes),
            ])->save();
        // Con Fill() y save() no hace falta meter todos los atributos en la petición sólo los que modifiquemos
        // Con el segundo parámetro de input() nos aseguramos que si no pasamos un atributo coja los del libro por defecto
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
