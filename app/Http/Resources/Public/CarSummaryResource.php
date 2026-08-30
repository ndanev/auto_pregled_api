<?php

namespace App\Http\Resources\Public;

use App\Enums\BodyType;
use App\Enums\Transmission;
use App\Models\AiAnalysis;
use App\Models\Engine;
use App\Models\Generation;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $slug
 * @property Transmission $transmission
 * @property BodyType|null $body_type
 * @property Generation $generation
 * @property Engine $engine
 * @property AiAnalysis|null $aiAnalysis
 * @property Collection <int, Image> $images
 */
class CarSummaryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $mainImage = $this->images->firstWhere('is_main', true) ?? $this->images->first();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'brand' => $this->generation->model->brand->name,
            'model' => $this->generation->model->name,
            'generation' => $this->generation->name,
            'years' => $this->generation->year_end
                ? "{$this->generation->year_start}–{$this->generation->year_end}"
                : "{$this->generation->year_start}–danas",
            'engine' => $this->engine->name,
            'power_hp' => $this->engine->power_hp,
            'fuel_type' => $this->engine->fuel_type,
            'transmission' => $this->transmission,
            'body_type' => $this->body_type,
            'overall_rating' => $this->aiAnalysis?->overall_rating,
            'main_image_url' => $mainImage?->path ? asset('storage/'.$mainImage->path) : null,
            'main_thumbnail_url' => $mainImage?->path ? asset('storage/'.preg_replace('/\.webp$/', '-thumb.webp', $mainImage->path)) : null,
            'brand_logo_url' => $this->generation->model->brand->logo_path ? Storage::disk('public')->url($this->generation->model->brand->logo_path) : null,
        ];
    }
}
