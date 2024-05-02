<?php

namespace App\Http\Requests\Series;

use Illuminate\Foundation\Http\FormRequest;

class SeriesRequest extends FormRequest
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
            'data.attributes.actors' => 'min:4',
            'data.attributes.num_seasons' => 'numeric',
            'data.attributes.num_episodes' => 'numeric',
            'data.attributes.currently_at' => 'max:150',
            'data.attributes.synopsis' => 'max:1000',
            'data.attributes.notes' => 'max:500',
        ];
    }
}
