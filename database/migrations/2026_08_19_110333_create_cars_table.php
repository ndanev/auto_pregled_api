<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generation_id')->constrained('generations')->cascadeOnDelete();
            $table->foreignId('engine_id')->constrained('engines')->cascadeOnDelete();
            $table->string('transmission');
            $table->string('drivetrain')->nullable();
            $table->string('body_type')->nullable();
            $table->string('status')->default('draft');
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            $table->index('generation_id');
            $table->index('engine_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};