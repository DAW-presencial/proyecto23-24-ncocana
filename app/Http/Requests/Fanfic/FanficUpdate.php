<?php

namespace App\Http\Requests\Fanfic;

use Illuminate\Foundation\Http\FormRequest;

class FanficUpdate extends FormRequest
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

            'title' => 'min:2|max:25',
            'author' => 'min:2|max:25',
            'language' => 'min:2|max:10',
            'fandom' => 'min:3|max:25',
            'relationships' => 'min:3|max:50',
            'words' => 'numeric',
            'read_chapters' => 'numeric',
            'total_chapters' => 'numeric',
            'synopsis' => 'min:3|max:500',
            'notes' => 'min:3|max:355'
        ];
    }
}
