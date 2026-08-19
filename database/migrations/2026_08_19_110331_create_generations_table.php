<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id')->constrained('models')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->smallInteger('year_start');
            $table->smallInteger('year_end')->nullable();
            $table->timestamps();

            $table->unique(['model_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generations');
    }
};