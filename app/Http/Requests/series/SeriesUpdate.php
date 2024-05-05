<?php

namespace App\Http\Requests\series;

use Illuminate\Foundation\Http\FormRequest;

class SeriesUpdate extends FormRequest
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
            'title' => 'min:2',
            'actors' => 'min:4',
            'num_seasons' => 'numeric',
            'num_episodes' => 'numeric',
            'currently_at' => 'max:150',
            'synopsis' => 'max:1000',
            'notes' => 'max:500',
        ];
    }
}
