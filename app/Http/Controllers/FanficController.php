<?php

namespace App\Http\Controllers;

use App\Http\Requests\Fanfic\FanficRequest;
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

    public function update(Fanfic $fanfic, FanficRequest $request)
    {
        // Get validated input data directly
        $validatedData = $request->input();
        
        // Update the fanfic using validated data
        // If some field is not in the request, use the existing data from the model instead
        $fanfic->update([
            'author' => $validatedData['bookmarkable']['author'] ?? $fanfic->author,
            'language' => $validatedData['bookmarkable']['language'] ?? $fanfic->language,
            'fandom' => $validatedData['bookmarkable']['fandom'] ?? $fanfic->language,
            'relationships' => $validatedData['bookmarkable']['relationships'] ?? $fanfic->language,
            'words' => $validatedData['bookmarkable']['words'] ?? $fanfic->language,
            'read_chapters' => $validatedData['bookmarkable']['read_chapters'] ?? $fanfic->language,
            'total_chapters' => $validatedData['bookmarkable']['total_chapters'] ?? $fanfic->language,
        ]);

        // Get the first bookmark associated with the fanfic
        $bookmark = $fanfic->bookmarks()->first();
        // Update the bookmark associated with the fanfic and user
        if ($bookmark) {
            $bookmark->update([
                'title' => $validatedData['title'] ?? $bookmark->title,
                'synopsis' => $validatedData['synopsis'] ?? $bookmark->synopsis,
                'notes' => $validatedData['notes'] ?? $bookmark->notes,
            ]);
        }

        // Return the fanfic resource
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
