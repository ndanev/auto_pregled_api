<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $car_id
 * @property string $model
 * @property int $prompt_tokens
 * @property int $completion_tokens
 * @property int $total_tokens
 * @property float $estimated_cost_usd
 * @property string $status
 * @property string|null $error_message
 */
class AiUsageLog extends Model
{
    protected $fillable = [
        'car_id', 'model', 'prompt_tokens', 'completion_tokens',
        'total_tokens', 'estimated_cost_usd', 'status', 'error_message',
    ];

    protected function casts(): array
    {
        return [
            'estimated_cost_usd' => 'float',
        ];
    }

    /**
     * @return BelongsTo<Car, $this>
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
