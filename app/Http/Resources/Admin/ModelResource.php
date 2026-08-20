<?php

namespace App\Http\Resources\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $brand_id
 * @property string $name
 * @property string $slug
 * @property Brand $brand
 * @property int|null $generations_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ModelResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'brand_id' => $this->brand_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'generations_count' => $this->whenCounted('generations'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
