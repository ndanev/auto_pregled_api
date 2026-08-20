<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Generation extends Model
{
    protected $fillable = ['model_id', 'name', 'slug', 'year_start', 'year_end'];

    /**
     * @return BelongsTo<CarModel, $this>
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }

    /**
     * @return HasMany<Engine, $this>
     */
    public function engines(): HasMany
    {
        return $this->hasMany(Engine::class);
    }

    /**
     * @return HasMany<Car, $this>
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
