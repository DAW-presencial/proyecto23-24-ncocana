<?php

namespace App\Http\Controllers;

use App\Http\Requests\Collection\CollectionRequest;
use App\Http\Resources\Collection\CollectionResource;
use App\Models\Bookmark;
use App\Models\Collection;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{

    public function __construct()  //Aplica el Sanctum a los métodos store, update y delete
    {
        $this->middleware('auth:sanctum');

    }

    public function index()
    {
        $collection = Collection::query()     // Se usan mixins para extender builder y aplicar parámetros en la búsqueda
            ->allowedSorts(['name', 'description'])
            ->allowedFilters(['name', 'description'])
            ->jsonPaginate();

        return CollectionResource::collection($collection);
        //Se utiliza un resource para la adhesión a la especificación ApiJson de la respuesta
    }

    public function store(CollectionRequest $request) // Se utiliza un form request para la validación
    {
        $collection = Collection::create([
            'user_id' => Auth::id(),
            'name' => $request->input("data.attributes.name"),
            'description' => $request->input("data.attributes.description"),
        ]);

        return CollectionResource::make($collection);
    }

    public function show(Collection $collection)
    {
        return CollectionResource::make($collection);
    }

    public function update(CollectionRequest $request, Collection $collection)
    {
        //Se utiliza un formRequest especial para la validación que no tenga los campos title y director requeridos
        $collection->fill([
            'name' => $request->input('data.attributes.name', $collection->name),
            'description' => $request->input('data.attributes.description', $collection->description),
            'user_id' => $collection->user_id
        ])->save();
        // Con Fill() y save() no hace falta meter todos los atributos en la petición sólo los que modifiquemos
        // Con el segundo parámetro de input() nos aseguramos que si no pasamos un atributo coja los del objeto por defecto


        return CollectionResource::make($collection);
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return response()->json([
            "succes" => "La colección " . $collection->id . " ha sido borrada con éxito"
        ]);
    }

    public function addBookmark(Collection $collection, Bookmark $bookmark)
    {
        $collection->bookmarks()->attach($bookmark);
        return response()->json([
            "succes" => "El marcador " . $bookmark->id . " ha sido añadido a la colección " . $collection->id
        ]);
    }

    public function removeBookmark(Collection $collection, Bookmark $bookmark)
    {
        $collection->bookmarks()->detach($bookmark);
        return response()->json([
            "succes" => "El marcador " . $bookmark->id . " ha sido eliminado de la colección " . $collection->id
        ]);
    }
}
