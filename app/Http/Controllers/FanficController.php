<?php

namespace App\Http\Controllers;

use App\Http\Requests\Fanfic\FanficRequest;
use App\Http\Requests\Fanfic\FanficUpdate;
use App\Http\Resources\Fanfic\FanficResource;
use App\Models\Fanfic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FanficController extends Controller
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
        $fanfics = Fanfic::query()     // Se usan mixins para extender builder y aplicar parámetros en la búsqueda
            ->allowedSorts(['author', 'language', 'fandom', 'relationships', 'words', 'read_chapters', 'total_chapters'])
            ->allowedFilters(['author', 'language', 'fandom', 'relationships', 'words', 'read_chapters', 'total_chapters'])
            ->jsonPaginate();
        
        return FanficResource::collection($fanfics);
        //Se utiliza un resource para la adhesión a la especificación ApiJson de la respuesta
    }


    public function store(FanficRequest $request) // Se utiliza un form request para la validación
    {
        $fanfic= Fanfic::create([
            'author' => $request->author,
            'language' => $request->language,
            'fandom' => $request->fandom,
            'relationships' => $request->relationships,
            'words' => $request->words,
            'read_chapters' => $request->read_chapters,
            'total_chapters' => $request->total_chapters,
        ]);

        $user = Auth::id();  //Recoge el id del usuario autenticado

        $fanfic->bookmarks()->create([
            'user_id' => $user,
            'title' => $request->title,
            'synopsis' => $request->synopsis,
            'notes' => $request->notes,
        ]);
        //Crea un Fanficmark relacioando al libro y al usuario autenticado

        return FanficResource::make($fanfic);
    }

  
    public function show(Fanfic $fanfic)
    {
        return FanficResource::make($fanfic);
    }


    public function update(FanficUpdate $request, Fanfic $fanfic) { 
        //Se utiliza un formRequest especial para la validación que no tenga los campos title y author requeridos
        $fanfic->fill([
            'author' => $request->input('author', $fanfic->author),
            'language' => $request->input('language', $fanfic->language),
            'fandom' => $request->input('fandom', $fanfic->fandom),
            'relationships' => $request->input('relationships', $fanfic->relationships),
            'words' => $request->input('words', $fanfic->words),
            'read_chapters' => $request->input('read_chapters', $fanfic->read_chapters),
            'total_chapters' => $request->input('total_chapters', $fanfic->total_chapters),
        ])->save();
        // Con Fill() y save() no hace falta meter todos los atributos en la petición sólo los que modifiquemos
        // Con el segundo parámetro de input() nos aseguramos que si no pasamos un atributo coja los del libro por defecto

        $fanfic->bookmarks()->update([
            'title' => $request->title,
            'synopsis' => $request->synopsis,
            'notes' => $request->notes,
        ]);

        FanficResource::make($fanfic);
    }

  
    public function destroy(Fanfic $fanfic)
    {
        $fanfic->delete();
        return response()->json([
            "Succes"=> "El fanfic ".$fanfic->id." ha sido eliminado con éxito"
        ]);
    }
}
