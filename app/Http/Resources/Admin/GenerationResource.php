<?php

namespace App\Http\Resources\Admin;

use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $model_id
 * @property string $name
 * @property string $slug
 * @property int $year_start
 * @property int|null $year_end
 * @property CarModel $model
 * @property int|null $engines_count
 * @property int|null $cars_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class GenerationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'model_id' => $this->model_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'year_start' => $this->year_start,
            'year_end' => $this->year_end,
            'model' => new ModelResource($this->whenLoaded('model')),
            'engines_count' => $this->whenCounted('engines'),
            'cars_count' => $this->whenCounted('cars'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
