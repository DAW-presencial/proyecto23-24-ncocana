<?php

namespace App\Http\Requests\book;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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

            'data.attributes.title' => 'required|min:2',
            'data.attributes.author' => 'required|min:2',
            'data.attributes.language' => 'max:25',
            'data.attributes.read_pages' => 'numeric',
            'data.attributes.total_pages'=> 'numeric',
            'data.attributes.synopsis' => 'max:1000',
            'data.attributes.notes' => 'max:500',

        ];
    }

    
}
