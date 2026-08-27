<?php

namespace App\Http\Resources\Public;

use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @property string $name
 * @property string $slug
 * @property Collection<int, CarModel> $models
 */
class BrandDetailResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'models' => $this->models->map(fn ($model) => [
                'name' => $model->name,
                'slug' => $model->slug,
                'cars_count' => $model->published_cars_count ?? 0,
            ]),
        ];
    }
}
