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

        $fanfics = Fanfic::query()
            ->where('user_id', $userId)
            ->allowedSorts(['author', 'language', 'fandom', 'relationships', 'words', 'read_chapters', 'total_chapters'])
            ->allowedFilters(['author', 'language', 'fandom', 'relationships', 'words', 'read_chapters', 'total_chapters'])
            ->jsonPaginate();
        
        return FanficResource::collection($fanfics);
    }

    public function store(FanficRequest $request)
    {
        // Get validated input data directly
        $validatedData = $request->input();
        
        // Create the fanfic using validated data
        $fanfic= Fanfic::create([
            'author' => $validatedData['bookmarkable']['author'],
            'language' => $validatedData['bookmarkable']['language'],
            'fandom' => $validatedData['bookmarkable']['fandom'],
            'relationships' => $validatedData['bookmarkable']['relationships'],
            'words' => $validatedData['bookmarkable']['words'],
            'read_chapters' => $validatedData['bookmarkable']['read_chapters'],
            'total_chapters' => $validatedData['bookmarkable']['total_chapters'],
        ]);

        // Get the authenticated user's ID
        $user = Auth::id();

        // Create the bookmark associated with the fanfic and user
        $fanfic->bookmarks()->create([
            'user_id' => $user,
            'title' => $validatedData['title'],
            'synopsis' => $validatedData['synopsis'],
            'notes' => $validatedData['notes'],
        ]);

        // Return the fanfic resource
        return FanficResource::make($fanfic);
    }

    public function show(Fanfic $fanfic)
    {
        // Get the currently authenticated user's ID
        $userId = Auth::id();

        // Check if the fanfic belongs to the current user
        if ($fanfic->user_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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

        return FanficResource::make($fanfic);
    }

    public function destroy(Fanfic $fanfic)
    {
        $fanfic->delete();

        return response()->json([
            "message"=> 'The fanfic "' . $fanfic->id . '" has been successfully deleted.'
        ]);
    }
}
