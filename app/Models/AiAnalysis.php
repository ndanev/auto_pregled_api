<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAnalysis extends Model
{
    protected $fillable = ['car_id', 'content'];

    protected function casts(): array
    {
        return [
            'content' => 'array',
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
