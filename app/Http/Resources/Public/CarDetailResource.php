<?php

namespace App\Http\Resources\Public;

use App\Enums\BodyType;
use App\Enums\Drivetrain;
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
 * @property Drivetrain|null $drivetrain
 * @property BodyType|null $body_type
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property Generation $generation
 * @property Engine $engine
 * @property AiAnalysis|null $aiAnalysis
 * @property Collection <int, Image> $images
 */
class CarDetailResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
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
            'displacement_cc' => $this->engine->displacement_cc,
            'power_hp' => $this->engine->power_hp,
            'torque_nm' => $this->engine->torque_nm,
            'fuel_type' => $this->engine->fuel_type,
            'transmission' => $this->transmission,
            'drivetrain' => $this->drivetrain,
            'body_type' => $this->body_type,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'analysis' => $this->aiAnalysis?->content,
            'overall_rating' => $this->aiAnalysis?->overall_rating,
            'images' => $this->images->map(fn ($image) => [
                'url' => Storage::disk('public')->url($image->path),
                'thumbnail_url' => Storage::disk('public')->url(preg_replace('/\.webp$/', '-thumb.webp', $image->path)),
                'is_main' => $image->is_main,
            ]),
        ];
    }
}
