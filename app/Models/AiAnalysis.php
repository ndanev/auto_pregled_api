<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property float|null $overall_rating
 * @property float|null $reliability_score
 * @property bool $has_insufficient_data
 */
class AiAnalysis extends Model
{
    protected $fillable = ['car_id', 'content'];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'overall_rating' => 'float',
            'reliability_score' => 'float',
            'has_insufficient_data' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Car, $this>
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    // overall_rating, reliability_score, has_insufficient_data
    // su generated kolone u bazi — Eloquent ih čita automatski,
    // ne treba ih dodavati u $fillable (baza ih sama izračunava)
}
