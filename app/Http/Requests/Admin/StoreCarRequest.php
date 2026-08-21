<?php

namespace App\Http\Requests\Admin;

use App\Enums\BodyType;
use App\Enums\CarStatus;
use App\Enums\Drivetrain;
use App\Enums\Transmission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreCarRequest extends FormRequest
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
            'engine_id' => ['required', 'integer', 'exists:engines,id'],
            'transmission' => ['required', new Enum(Transmission::class)],
            'drivetrain' => ['nullable', new Enum(Drivetrain::class)],
            'body_type' => ['nullable', new Enum(BodyType::class)],
            'status' => ['required', new Enum(CarStatus::class)],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ];
    }
}
