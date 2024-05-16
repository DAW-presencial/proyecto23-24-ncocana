<?php

namespace App\Http\Requests\Bookmark;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookmarkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.bookmarkable_type' => [
                'required',
                Rule::in(['Movie', 'Series', 'Book', 'Fanfic']),
            ],
            'data.attributes.title' => 'required|string|max:100',
            'data.attributes.synopsis' => 'nullable|string|max:1000',
            'data.attributes.notes' => 'nullable|string|max:2000',
            'data.attributes.bookmarkable.author' => 'nullable|string',
            'data.attributes.bookmarkable.language' => 'nullable|string|max:50',
            'data.attributes.bookmarkable.read_pages' => 'nullable|numeric',
            'data.attributes.bookmarkable.total_pages' => 'nullable|numeric',
            'data.attributes.bookmarkable.fandom' => 'nullable|string|max:100',
            'data.attributes.bookmarkable.relationships' => 'nullable|string|max:100',
            'data.attributes.bookmarkable.words' => 'nullable|numeric',
            'data.attributes.bookmarkable.read_chapters' => 'nullable|numeric',
            'data.attributes.bookmarkable.total_chapters' => 'nullable|numeric',
            'data.attributes.bookmarkable.director' => 'nullable|string',
            'data.attributes.bookmarkable.actors' => 'nullable|string|max:500',
            'data.attributes.bookmarkable.release_date' => 'nullable|date',
            'data.attributes.bookmarkable.num_seasons' => 'nullable|numeric',
            'data.attributes.bookmarkable.num_episodes' => 'nullable|numeric',
            'data.attributes.bookmarkable.currently_at' => 'nullable|string|max:50',
        ];

        // Validate 'tags' only if it's a string or an array
        if (is_string($this->input('data.attributes.tags')) || is_array($this->input('data.attributes.tags'))) {
            $rules['data.attributes.tags'] = 'nullable';
        }

        return $rules;
    }
}
