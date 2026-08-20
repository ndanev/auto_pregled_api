<?php

namespace App\Http\Resources\Admin;

use App\Enums\FuelType;
use App\Models\Generation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $generation_id
 * @property string $name
 * @property FuelType $fuel_type
 * @property int|null $displacement_cc
 * @property int|null $power_hp
 * @property int|null $torque_nm
 * @property Generation $generation
 * @property int|null $cars_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class EngineResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'generation_id' => $this->generation_id,
            'name' => $this->name,
            'fuel_type' => $this->fuel_type,
            'displacement_cc' => $this->displacement_cc,
            'power_hp' => $this->power_hp,
            'torque_nm' => $this->torque_nm,
            'generation' => new GenerationResource($this->whenLoaded('generation')),
            'cars_count' => $this->whenCounted('cars'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
