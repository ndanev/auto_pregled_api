<?php

namespace App\Models;

use App\Enums\BodyType;
use App\Enums\CarStatus;
use App\Enums\Drivetrain;
use App\Enums\Transmission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Car extends Model
{
    protected $fillable = [
        'generation_id', 'engine_id', 'transmission', 'drivetrain',
        'body_type', 'status', 'slug', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'transmission' => Transmission::class,
            'drivetrain' => Drivetrain::class,
            'body_type' => BodyType::class,
            'status' => CarStatus::class,
        ];
    }

    /**
     * @return BelongsTo<Generation, $this>
     */
    public function generation(): BelongsTo
    {
        return $this->belongsTo(Generation::class);
    }

    /**
     * @return BelongsTo<Engine, $this>
     */
    public function engine(): BelongsTo
    {
        return $this->belongsTo(Engine::class);
    }

    /**
     * @return HasMany<Image, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    /**
     * @return HasOne<AiAnalysis, $this>
     */
    public function aiAnalysis(): HasOne
    {
        return $this->hasOne(AiAnalysis::class);
    }
}
