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
 
    public function index()
    {
        $fanfics = Fanfic::query()     // Se usan mixins para extender builder y aplicar parámetros en la búsqueda
        ->allowedSorts(['title', 'author'])
        ->jsonPaginate();
        
        return FanficResource::collection($fanfics);
        //Se utiliza un resource para la adhesión a la especificación ApiJson de la respuesta
    }


    public function store(FanficRequest $request) // Se utiliza un form request para la validación
    {
        $fanfic= Fanfic::create([
            'title' => $request->input('data.attributes.title'),
            'author' => $request->input('data.attributes.author'),
            'language' => $request->input('data.attributes.language'),
            'fandom' => $request->input('data.attributes.fandom'),
            'relationships' => $request->input('data.attributes.relationships'),
            'words' => $request->input('data.attributes.words'),
            'read_chapters' => $request->input('data.attributes.read_chapters'),
            'total_chapters' => $request->input('data.attributes.total_chapters'),
            'synopsis' => $request->input('data.attributes.synopsis'),
            'notes' => $request->input('data.attributes.notes'),
        ]);

        $user = Auth::id();  //Recoge el id del usuario autenticado

        $fanfic->Bookmarks()->create(['user_id' => $user]);
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
                'title' => $request->input('data.attributes.title', $fanfic->title),
                'author' => $request->input('data.attributes.author', $fanfic->author),
                'language' => $request->input('data.attributes.language', $fanfic->language),
                'fandom' => $request->input('data.attributes.fandom', $fanfic->fandom),
                'relationships' => $request->input('data.attributes.relationships', $fanfic->relationships),
                'words' => $request->input('data.attributes.words', $fanfic->words),
                'read_chapters' => $request->input('data.attributes.read_chapters', $fanfic->read_chapters),
                'total_chapters' => $request->input('data.attributes.total_chapters', $fanfic->total_chapters),
                'synopsis' => $request->input('data.attributes.synopsis', $fanfic->synopsis),
                'notes' => $request->input('data.attributes.notes', $fanfic->notes),
            ])->save();
        // Con Fill() y save() no hace falta meter todos los atributos en la petición sólo los que modifiquemos
        // Con el segundo parámetro de input() nos aseguramos que si no pasamos un atributo coja los del libro por defecto
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
