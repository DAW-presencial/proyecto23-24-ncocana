<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\Constraint\IsTrue;
use SebastianBergmann\Type\TrueType;

class MovieUpdate extends FormRequest
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

            'data.attributes.title' => 'min:2',
            'data.attributes.director' => 'min:4',
            'data.attributes.actors' => 'max:500',
            'data.attributes.synopsis' => 'max:1000',
            'data.attributes.notes' => 'max:500',

        ];
    }
}
