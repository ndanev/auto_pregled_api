<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGenerationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'model_id' => ['required', 'integer', 'exists:models,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('generations', 'slug')
                    ->where('model_id', $this->input('model_id'))
                    ->ignore($this->route('generation')),
            ],
            'year_start' => ['required', 'integer', 'min:1900', 'max:2100'],
            'year_end' => ['nullable', 'integer', 'min:1900', 'max:2100', 'gte:year_start'],
        ];
    }
}
