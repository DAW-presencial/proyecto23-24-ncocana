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
            'director' => 'string',
            'actors' => 'string|max:500',
            'release_date' => 'date',
            'currently_at' => 'string|max:10',
        ];
    }
}
