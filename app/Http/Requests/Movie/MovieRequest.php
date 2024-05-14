<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
{
  
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
        return [
            'title' => 'required|string|max:100',
            'synopsis' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
            'bookmarkable.director' => 'string',
            'bookmarkable.actors' => 'string|max:500',
            'bookmarkable.release_date' => 'date',
            'bookmarkable.currently_at' => 'string|max:10',
        ];
    }
}
