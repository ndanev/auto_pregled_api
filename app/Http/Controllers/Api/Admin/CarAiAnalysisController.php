<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AiAnalysisResource;
use App\Models\AiAnalysis;
use App\Models\Car;
use App\Services\AI\AiAnalysisGenerationException;
use App\Services\AI\AiAnalysisGeneratorInterface;
use Illuminate\Http\JsonResponse;

class CarAiAnalysisController extends Controller
{
    public function __construct(
        private readonly AiAnalysisGeneratorInterface $generator,
    ) {}

    public function generate(Car $car): JsonResponse
    {
        $this->authorize('update', $car);

        set_time_limit(120);

        try {
            $content = $this->generator->generate($car);
        } catch (AiAnalysisGenerationException $e) {
            return response()->json([
                'message' => 'Generisanje AI analize nije uspelo: '.$e->getMessage(),
            ], 422);
        }

        $content['meta'] = [
            'generated_at' => now()->toIso8601String(),
            'ai_model' => config('services.openai.model'),
            'schema_version' => '1.0',
        ];

        $analysis = AiAnalysis::updateOrCreate(
            ['car_id' => $car->id],
            ['content' => $content],
        );

        return (new AiAnalysisResource($analysis))->response();
    }
}
