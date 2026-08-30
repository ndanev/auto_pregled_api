<?php

namespace App\Services\AI;

class CarAnalysisSchema
{
    /**
     * Vraća JSON Schema (strict mode) koju OpenAI garantuje da će odgovor pratiti.
     * Namerno ne uključuje "meta" — to popunjava backend, ne AI.
     *
     * @return array<string, mixed>
     */
    public static function definition(): array
    {
        $ratingNumber = ['type' => 'number'];
        $stringArray = ['type' => 'array', 'items' => ['type' => 'string']];

        $titledItem = [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string'],
                'description' => ['type' => 'string'],
            ],
            'required' => ['title', 'description'],
            'additionalProperties' => false,
        ];

        return [
            'name' => 'car_analysis',
            'strict' => true,
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'data_quality' => [
                        'type' => 'object',
                        'properties' => [
                            'insufficient_sections' => $stringArray,
                        ],
                        'required' => ['insufficient_sections'],
                        'additionalProperties' => false,
                    ],
                    'ai_summary' => [
                        'type' => 'object',
                        'properties' => [
                            'short_description' => ['type' => 'string'],
                            'overall_rating' => $ratingNumber,
                            'market_position' => ['type' => 'string'],
                            'final_verdict' => ['type' => 'string'],
                        ],
                        'required' => ['short_description', 'overall_rating', 'market_position', 'final_verdict'],
                        'additionalProperties' => false,
                    ],
                    'target_audience' => [
                        'type' => 'object',
                        'properties' => [
                            'ideal_for' => $stringArray,
                            'not_recommended_for' => $stringArray,
                        ],
                        'required' => ['ideal_for', 'not_recommended_for'],
                        'additionalProperties' => false,
                    ],
                    'strengths' => ['type' => 'array', 'items' => $titledItem],
                    'weaknesses' => ['type' => 'array', 'items' => $titledItem],
                    'reliability' => [
                        'type' => 'object',
                        'properties' => [
                            'score' => $ratingNumber,
                            'engine_reliability' => $ratingNumber,
                            'transmission_reliability' => $ratingNumber,
                            'electronics_reliability' => $ratingNumber,
                        ],
                        'required' => ['score', 'engine_reliability', 'transmission_reliability', 'electronics_reliability'],
                        'additionalProperties' => false,
                    ],
                    'maintenance' => [
                        'type' => 'object',
                        'properties' => [
                            'cost_level' => ['type' => 'string', 'enum' => ['low', 'medium', 'high']],
                            'annual_estimate' => ['type' => 'string'],
                            'common_repairs' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'problem' => ['type' => 'string'],
                                        'estimated_cost' => ['type' => 'string'],
                                    ],
                                    'required' => ['problem', 'estimated_cost'],
                                    'additionalProperties' => false,
                                ],
                            ],
                        ],
                        'required' => ['cost_level', 'annual_estimate', 'common_repairs'],
                        'additionalProperties' => false,
                    ],
                    'fuel_consumption' => [
                        'type' => 'object',
                        'properties' => [
                            'city' => ['type' => 'string'],
                            'highway' => ['type' => 'string'],
                            'combined' => ['type' => 'string'],
                        ],
                        'required' => ['city', 'highway', 'combined'],
                        'additionalProperties' => false,
                    ],
                    'driving_experience' => [
                        'type' => 'object',
                        'properties' => [
                            'comfort' => $ratingNumber,
                            'performance' => $ratingNumber,
                            'handling' => $ratingNumber,
                            'noise_level' => $ratingNumber,
                        ],
                        'required' => ['comfort', 'performance', 'handling', 'noise_level'],
                        'additionalProperties' => false,
                    ],
                    'buying_guide' => [
                        'type' => 'object',
                        'properties' => [
                            'recommended_engines' => $stringArray,
                            'engines_to_avoid' => $stringArray,
                            'inspection_points' => $stringArray,
                        ],
                        'required' => ['recommended_engines', 'engines_to_avoid', 'inspection_points'],
                        'additionalProperties' => false,
                    ],
                    'alternatives' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'model' => ['type' => 'string'],
                                'reason' => ['type' => 'string'],
                            ],
                            'required' => ['model', 'reason'],
                            'additionalProperties' => false,
                        ],
                    ],
                    'seo' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                            'keywords' => $stringArray,
                        ],
                        'required' => ['title', 'description', 'keywords'],
                        'additionalProperties' => false,
                    ],
                    'faq' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'question' => ['type' => 'string'],
                                'answer' => ['type' => 'string'],
                            ],
                            'required' => ['question', 'answer'],
                            'additionalProperties' => false,
                        ],
                    ],
                ],
                'required' => [
                    'data_quality', 'ai_summary', 'target_audience', 'strengths', 'weaknesses',
                    'reliability', 'maintenance', 'fuel_consumption', 'driving_experience',
                    'buying_guide', 'alternatives', 'seo', 'faq',
                ],
                'additionalProperties' => false,
            ],
        ];
    }
}
