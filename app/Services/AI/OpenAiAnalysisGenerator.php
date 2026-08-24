<?php

namespace App\Services\AI;

use App\Models\Car;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonException;

class OpenAiAnalysisGenerator implements AiAnalysisGeneratorInterface
{
    /**
     * @return array<string, mixed>
     */
    public function generate(Car $car): array
    {
        $prompt = $this->buildPrompt($car);

        $response = Http::withToken(config('services.openai.api_key'))
            ->timeout(60)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model'),
                'messages' => [
                    ['role' => 'system', 'content' => $this->systemPrompt()],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => CarAnalysisSchema::definition(),
                ],
            ]);

        if ($response->failed()) {
            Log::error('OpenAI analysis request failed', [
                'car_id' => $car->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new AiAnalysisGenerationException(
                "OpenAI API zahtev nije uspeo (status {$response->status()})."
            );
        }

        $content = $response->json('choices.0.message.content');

        if (! is_string($content)) {
            throw new AiAnalysisGenerationException('OpenAI odgovor nema očekivan sadržaj.');
        }

        try {
            $decoded = json_decode($content, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            Log::error('OpenAI returned invalid JSON', ['car_id' => $car->id, 'content' => $content]);

            throw new AiAnalysisGenerationException('AI je vratio neispravan JSON.', previous: $e);
        }

        if (! is_array($decoded)) {
            throw new AiAnalysisGenerationException('AI odgovor nije JSON objekat.');
        }

        return $decoded;
    }

    private function systemPrompt(): string
    {
        return <<<'PROMPT'
            Ti si stručnjak za automobilsku industriju koji piše objektivne, korisne
            AI preglede automobila za platformu Auto Pregled. Odgovaraj isključivo na
            srpskom jeziku. Nikada ne izmišljaj konkretne brojeve, statistike ili
            činjenice koje nisu opšte poznate za dati model. Ako za neku sekciju nemaš
            dovoljno pouzdanih informacija, dodaj tačan naziv te sekcije u
            "data_quality.insufficient_sections" i popuni to polje najboljom
            razumnom procenom uz jasnu naznaku da je procena, ne izmišljenu preciznu
            činjenicu. Razlikuj proverene činjenice od opštih iskustava i procena.
            PROMPT;
    }

    private function buildPrompt(Car $car): string
    {
        $generation = $car->generation;
        $model = $generation->model;
        $brand = $model->brand;
        $engine = $car->engine;

        $years = $generation->year_end
            ? "{$generation->year_start}-{$generation->year_end}"
            : "{$generation->year_start}-danas";

        return <<<PROMPT
            Napravi detaljnu AI analizu za sledeći automobil:

            Marka: {$brand->name}
            Model: {$model->name}
            Generacija: {$generation->name} ({$years})
            Motor: {$engine->name}
            Tip goriva: {$engine->fuel_type->value}
            Zapremina: {$engine->displacement_cc} cm³
            Snaga: {$engine->power_hp} KS
            Obrtni moment: {$engine->torque_nm} Nm
            Menjač: {$car->transmission->value}
            Pogon: {$car->drivetrain?->value}
            Karoserija: {$car->body_type?->value}
            PROMPT;
    }
}
