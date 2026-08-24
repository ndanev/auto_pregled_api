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
use Illuminate\Support\Str;

/**
 * @property Transmission $transmission
 * @property Drivetrain|null $drivetrain
 * @property BodyType|null $body_type
 */
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

    /**
     * Generiše jedinstven slug na osnovu marke, modela, generacije, motora,
     * menjača i (ako postoji) tipa karoserije — sa numeričkim sufiksom ako
     * već postoji identičan slug (npr. isti auto sa različitim body_type).
     */
    public static function generateSlug(Generation $generation, Engine $engine, Transmission $transmission, ?BodyType $bodyType): string
    {
        $parts = [
            $generation->model->brand->name,
            $generation->model->name,
            $generation->name,
            $engine->name,
            $transmission->value,
        ];

        if ($bodyType !== null) {
            $parts[] = $bodyType->value;
        }

        $base = Str::slug(implode(' ', $parts));
        $slug = $base;
        $suffix = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
