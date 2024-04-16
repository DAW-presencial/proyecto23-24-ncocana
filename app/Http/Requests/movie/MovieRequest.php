<?php

namespace App\Http\Requests\movie;

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

            'data.attributes.title' => 'required|min:2',
            'data.attributes.director' => 'required|min:4',
            'data.attributes.actors' => 'max:500',
            'data.attributes.release_date' ,
            'data.attributes.currently_at',
            'data.attributes.synopsis' => 'max:1000',
            'data.attributes.notes' => 'max:500',

        ];
    }
}
