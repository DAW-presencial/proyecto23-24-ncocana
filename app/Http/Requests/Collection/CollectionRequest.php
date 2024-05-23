<?php

namespace App\Http\Requests\Collection;

use Illuminate\Foundation\Http\FormRequest;

class CollectionRequest extends FormRequest
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
        $rules = [
            'data.type' => 'required|in:collections',
            'data.attributes.name' => 'required|string|max:100',
            'data.attributes.description' => 'string|max:500',
        ];

        // Validate 'tags' only if it's a string or an array
        if (is_string($this->input('data.attributes.tags')) || is_array($this->input('data.attributes.tags'))) {
            $rules['data.attributes.tags'] = 'nullable';
        }

        return $rules;
    }
}
