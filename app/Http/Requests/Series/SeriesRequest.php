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
            'title' => 'required|string|max:100',
            'synopsis' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
            'bookmarkable.actors' => 'string|max:500',
            'bookmarkable.num_seasons' => 'numeric',
            'bookmarkable.num_episodes' => 'numeric',
            'bookmarkable.currently_at' => 'string|max:50',
        ];
    }
}
