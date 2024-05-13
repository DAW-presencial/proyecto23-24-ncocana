<?php

namespace App\Http\Requests\Fanfic;

use Illuminate\Foundation\Http\FormRequest;

class FanficRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:100',
            'synopsis' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
            'bookmarkable.author' => 'required|string',
            'bookmarkable.language' => 'nullable|string|max:50',
            'bookmarkable.fandom' => 'string|max:100',
            'bookmarkable.relationships' => 'string|max:100',
            'bookmarkable.words' => 'numeric',
            'bookmarkable.read_chapters' => 'numeric',
            'bookmarkable.total_chapters' => 'numeric',
        ];
    }
}
