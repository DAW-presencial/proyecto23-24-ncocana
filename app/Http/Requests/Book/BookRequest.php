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

            'title' => 'required|min:2',
            'author' => 'required|min:2',
            'language' => 'max:25',
            'read_pages' => 'numeric',
            'total_pages'=> 'numeric',
            'synopsis' => 'max:1000',
            'notes' => 'max:500',

        ];
    }

    
}
