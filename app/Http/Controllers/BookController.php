<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\BookRequest;
use App\Http\Requests\Book\BookUpdate;
use App\Http\Resources\Book\BookResource;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
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
        $books = Book::query()     // Se usan mixins para extender builder y aplicar parámetros en la búsqueda
            ->allowedSorts(['author', 'language', 'read_pages', 'total_pages'])
            ->allowedFilters(['author', 'language', 'read_pages', 'total_pages'])
            ->jsonPaginate();
        
        return BookResource::collection($books);
        //Se utiliza un resource para la adhesión a la especificación ApiJson de la respuesta
    }


    public function store(BookRequest $request) // Se utiliza un form request para la validación
    {
        $book= Book::create([
            'author' => $request->author,
            'language' => $request->language,
            'read_pages' => $request->read_pages,
            'total_pages' => $request->total_pages,
        ]);

        $user = Auth::id();  //Recoge el id del usuario autenticado

        $book->bookmarks()->create([
            'user_id' => $user,
            'title' => $request->title,
            'synopsis' => $request->synopsis,
            'notes' => $request->notes,
        ]);
        //Crea un bookmark relacioando al libro y al usuario autenticado

        return BookResource::make($book);
    }


  
    public function show(Book $book)
    {
        return BookResource::make($book);
    }



    public function update(BookUpdate $request, Book $book) { 
        //Se utiliza un formRequest especial para la validación que no tenga los campos title y author requeridos
        $book->fill([
            'author' => $request->input('author', $book->author),
            'language' => $request->input('language', $book->language),
            'read_pages' => $request->input('read_pages', $book->read_pages),
            'total_pages' => $request->input('total_pages', $book->total_pages),
        ])->save();
        // Con Fill() y save() no hace falta meter todos los atributos en la petición sólo los que modifiquemos
        // Con el segundo parámetro de input() nos aseguramos que si no pasamos un atributo coja los del libro por defecto
        
        $book->bookmarks()->update([
            'title' => $request->title,
            'synopsis' => $request->synopsis,
            'notes' => $request->notes,
        ]);
        
        BookResource::make($book);
    }


  
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json([
            "Succes"=> "Libro ".$book->id." ha sido eliminado con éxito"
        ]);
    }
}
