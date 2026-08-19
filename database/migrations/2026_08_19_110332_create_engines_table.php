<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('engines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generation_id')->constrained('generations')->cascadeOnDelete();
            $table->string('name');
            $table->string('fuel_type');
            $table->integer('displacement_cc')->nullable();
            $table->integer('power_hp')->nullable();
            $table->integer('torque_nm')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('engines');
    }
};