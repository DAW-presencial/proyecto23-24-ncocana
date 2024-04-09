<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Database\Factories\BookFactory;
use Illuminate\Http\Request;

class BookController extends Controller
{
   
    public function index()
    {
        $books = Book::query()
        ->allowedSorts(['title', 'author'])
        ->jsonPaginate();
        
        return BookResource::collection($books);
    }


    public function store(BookRequest $request)
    {
        $book= Book::create([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'language' => $request->input('language'),
            'read_pages' => $request->input('read_pages'),
            'total_pages' => $request->input('total_pages'),
            'synopsis' => $request->input('synopsis'),
            'notes' => $request->input('notes'),
        ]);

        BookResource::make($book);
    }


  
    public function show(Book $book)
    {
        BookResource::make($book);
    }



    public function update(BookRequest $request, Book $book)
    {
        $book->update([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'language' => $request->input('language'),
            'read_pages' => $request->input('read_pages'),
            'total_pages' => $request->input('total_pages'),
            'synopsis' => $request->input('synopsis'),
            'notes' => $request->input('notes'),
        ]);

        BookResource::make($book);
    }


  
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json([
            "Succes"=> "Libro ".$book->id." eliminado"
        ]);
    }
}
