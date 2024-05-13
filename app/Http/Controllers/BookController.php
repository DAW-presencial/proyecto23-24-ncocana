<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\BookRequest;
use App\Http\Resources\Book\BookResource;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
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

        $books = Book::query()
            ->where('user_id', $userId)
            ->allowedSorts(['author', 'language', 'read_pages', 'total_pages'])
            ->allowedFilters(['author', 'language', 'read_pages', 'total_pages'])
            ->jsonPaginate();
        
        return BookResource::collection($books);
    }

    public function store(BookRequest $request)
    {
        // Get validated input data directly
        $validatedData = $request->input();
        
        // Create the book using validated data
        $book = Book::create([
            'author' => $validatedData['bookmarkable']['author'],
            'language' => $validatedData['bookmarkable']['language'],
            'read_pages' => $validatedData['bookmarkable']['read_pages'],
            'total_pages' => $validatedData['bookmarkable']['total_pages'],
        ]);

        // Get the authenticated user's ID
        $user = Auth::id();

        // Create the bookmark associated with the book and user
        $book->bookmarks()->create([
            'user_id' => $user,
            'title' => $validatedData['title'],
            'synopsis' => $validatedData['synopsis'],
            'notes' => $validatedData['notes'],
        ]);
        // dd($book->bookmarks()->with('bookmarkable')->get());

        // Return the book resource
        return BookResource::make($book);
    }
  
    public function show(Book $book)
    {
        // Get the currently authenticated user's ID
        $userId = Auth::id();

        // Check if the book belongs to the current user
        if ($book->user_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return BookResource::make($book);
    }

    public function update(Book $book, BookRequest $request)
    {
        // Get validated input data directly
        $validatedData = $request->input();
        
        // Update the book using validated data
        // If some field is not in the request, use the existing data from the model instead
        $book->update([
            'author' => $validatedData['bookmarkable']['author'] ?? $book->author,
            'language' => $validatedData['bookmarkable']['language'] ?? $book->language,
            'read_pages' => $validatedData['bookmarkable']['read_pages'] ?? $book->read_pages,
            'total_pages' => $validatedData['bookmarkable']['total_pages'] ?? $book->total_pages,
        ]);
        
        // Get the first bookmark associated with the book
        $bookmark = $book->bookmarks()->first();
        // Update the bookmark associated with the book and user
        if ($bookmark) {
            $bookmark->update([
                'title' => $validatedData['title'] ?? $bookmark->title,
                'synopsis' => $validatedData['synopsis'] ?? $bookmark->synopsis,
                'notes' => $validatedData['notes'] ?? $bookmark->notes,
            ]);
        }
            
        // Return the book resource
        return BookResource::make($book);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            "message" => 'The book "' . $book->id . '" has been successfully deleted.'
        ]);
    }
}
