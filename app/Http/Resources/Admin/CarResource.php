<?php

namespace App\Http\Resources\Admin;

use App\Enums\BodyType;
use App\Enums\CarStatus;
use App\Enums\Drivetrain;
use App\Enums\Transmission;
use App\Models\AiAnalysis;
use App\Models\Engine;
use App\Models\Generation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $generation_id
 * @property int $engine_id
 * @property Transmission $transmission
 * @property Drivetrain|null $drivetrain
 * @property BodyType|null $body_type
 * @property CarStatus $status
 * @property string $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property Generation $generation
 * @property Engine $engine
 * @property AiAnalysis|null $aiAnalysis
 * @property int|null $images_count
 * @property bool $has_ai_analysis
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class CarResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'generation_id' => $this->generation_id,
            'engine_id' => $this->engine_id,
            'transmission' => $this->transmission,
            'drivetrain' => $this->drivetrain,
            'body_type' => $this->body_type,
            'status' => $this->status,
            'slug' => $this->slug,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'generation' => new GenerationResource($this->whenLoaded('generation')),
            'engine' => new EngineResource($this->whenLoaded('engine')),
            'images_count' => $this->whenCounted('images'),
            'has_ai_analysis' => (bool) $this->aiAnalysis,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
