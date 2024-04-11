<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdate extends FormRequest
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

            'data.attributes.title' => 'min:3',
            'data.attributes.author' => 'min:3',
            'data.attributes.language' => 'max:25',
            'data.attributes.read_pages' ,
            'data.attributes.total_pages',
            'data.attributes.synopsis' => 'max:1000',
            'data.attributes.notes' => 'max:500',

        ];
    }
}
