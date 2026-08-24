<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $car_id
 * @property array<string, mixed> $content
 * @property float|null $overall_rating
 * @property float|null $reliability_score
 * @property bool $has_insufficient_data
 * @property Carbon $updated_at
 */
class AiAnalysisResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'car_id' => $this->car_id,
            'content' => $this->content,
            'overall_rating' => $this->overall_rating,
            'reliability_score' => $this->reliability_score,
            'has_insufficient_data' => (bool) $this->has_insufficient_data,
            'updated_at' => $this->updated_at,
        ];
    }
}
