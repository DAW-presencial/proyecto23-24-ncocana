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
            'author' => 'required|string|max:50',
            'language' => 'string|max:50',
            'fandom' => 'string|max:100',
            'relationships' => 'string|max:100',
            'words' => 'numeric',
            'read_chapters' => 'numeric',
            'total_chapters' => 'numeric',
        ];
    }
}
