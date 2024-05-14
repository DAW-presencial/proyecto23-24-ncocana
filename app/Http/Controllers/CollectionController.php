<?php

namespace App\Http\Controllers;

use App\Http\Requests\Collection\CollectionRequest;
use App\Http\Requests\Collection\CollectionUpdate;
use App\Http\Resources\Collection\CollectionResource;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
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
        $collection = Collection::query()     // Se usan mixins para extender builder y aplicar parámetros en la búsqueda
            ->allowedSorts(['name', 'description'])
            ->allowedFilters(['name', 'description'])
            ->jsonPaginate();
        
        return CollectionResource::collection($collection);
        //Se utiliza un resource para la adhesión a la especificación ApiJson de la respuesta
    }

    public function store(CollectionRequest $request) // Se utiliza un form request para la validación
    {
        $collection= Collection::create([
            'name' => $request->name,
            'description' => $request->descrption,
            'user_id' => Auth::id()
        ]);

        collectionResource::make($collection);
    }

    public function show(Collection $collection)
    {
        return CollectionResource::make($collection);
    }

    public function update(CollectionUpdate $request, Collection $collection) { 
        //Se utiliza un formRequest especial para la validación que no tenga los campos title y director requeridos
        $collection->fill([
            'actors' => $request->input('actors', $collection->actors),
            'num_seasons' => $request->input('num_seasons', $collection->num_seasons),
            'user_id' => $collection->user_id
        ])->save();
        // Con Fill() y save() no hace falta meter todos los atributos en la petición sólo los que modifiquemos
        // Con el segundo parámetro de input() nos aseguramos que si no pasamos un atributo coja los del libro por defecto

        collectionResource::make($collection);
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return response()->json([
            "succes" =>"La colección ".$collection->id." ha sido borrada con éxito"
        ]);
    }
}
