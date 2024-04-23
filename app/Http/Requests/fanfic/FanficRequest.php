<?php

namespace App\Http\Requests\fanfic;

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
                'data.attributes.title' => 'required|min:2|max:25',
                'data.attributes.author' => 'required|min:2|max:25',
                'data.attributes.language' => 'min:2|max:10',
                'data.attributes.fandom' => 'min:3|max:25',
                'data.attributes.relationships' => 'min:3|max:50',
                'data.attributes.words' => 'numeric',
                'data.attributes.read_chapters' => 'numeric',
                'data.attributes.total_chapters' => 'numeric',
                'data.attributes.synopsis' => 'min:3|max:500',
                'data.attributes.notes' => 'min:3|max:355'
        ];
    }
}
