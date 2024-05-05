<?php

namespace App\Http\Requests\movie;

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

            'title' => 'min:2',
            'director' => 'min:4',
            'actors' => 'max:500',
            'synopsis' => 'max:1000',
            'notes' => 'max:500',

        ];
    }
}
