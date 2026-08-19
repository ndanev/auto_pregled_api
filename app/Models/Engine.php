<?php

namespace App\Models;

use App\Enums\FuelType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Engine extends Model
{
    protected $fillable = [
        'generation_id', 'name', 'fuel_type',
        'displacement_cc', 'power_hp', 'torque_nm',
    ];

    protected function casts(): array
    {
        return [
            'fuel_type' => FuelType::class,
        ];
    }

    public function generation(): BelongsTo
    {
        return $this->belongsTo(Generation::class);
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}