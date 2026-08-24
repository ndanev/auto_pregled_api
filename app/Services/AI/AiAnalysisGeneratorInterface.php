<?php

namespace App\Services\AI;

use App\Models\Car;

interface AiAnalysisGeneratorInterface
{
    /**
     * Generiše AI analizu za dati automobil i vraća validirani sadržaj
     * spreman za čuvanje u ai_analyses.content. Baca AiAnalysisGenerationException
     * ako provajder vrati grešku ili neispravan JSON.
     *
     * @return array<string, mixed>
     */
    public function generate(Car $car): array;
}
