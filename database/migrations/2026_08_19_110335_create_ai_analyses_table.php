<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->unique()->constrained('cars')->cascadeOnDelete();
            $table->json('content');
            $table->timestamps();
        });

        // Generated columns extracted from JSON — MySQL 8 specific syntax
        DB::statement("
            ALTER TABLE ai_analyses
            ADD COLUMN overall_rating DECIMAL(3,1)
                GENERATED ALWAYS AS (JSON_EXTRACT(content, '$.ai_summary.overall_rating')) STORED,
            ADD COLUMN reliability_score DECIMAL(3,1)
                GENERATED ALWAYS AS (JSON_EXTRACT(content, '$.reliability.score')) STORED,
            ADD COLUMN has_insufficient_data BOOLEAN
                GENERATED ALWAYS AS (JSON_LENGTH(JSON_EXTRACT(content, '$.data_quality.insufficient_sections')) > 0) STORED
        ");

        Schema::table('ai_analyses', function (Blueprint $table) {
            $table->index('overall_rating');
            $table->index('has_insufficient_data');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_analyses');
    }
};
