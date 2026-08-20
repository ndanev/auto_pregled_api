<?php

namespace App\Http\Requests\Admin;

use App\Enums\FuelType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateEngineRequest extends FormRequest
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
            'generation_id' => ['required', 'integer', 'exists:generations,id'],
            'name' => ['required', 'string', 'max:255'],
            'fuel_type' => ['required', new Enum(FuelType::class)],
            'displacement_cc' => ['nullable', 'integer', 'min:0'],
            'power_hp' => ['nullable', 'integer', 'min:0'],
            'torque_nm' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
