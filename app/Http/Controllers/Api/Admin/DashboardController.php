<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\CarStatus;
use App\Http\Controllers\Controller;
use App\Models\AiAnalysis;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\Generation;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $carsPerMonth = Car::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->pluck('total', 'month');

        $months = collect(range(5, 0))->map(function (int $i) use ($carsPerMonth) {
            $date = Carbon::now()->subMonths($i);
            $key = $date->format('Y-m');

            return [
                'label' => $date->translatedFormat('M'),
                'count' => (int) ($carsPerMonth[$key] ?? 0),
            ];
        });

        return response()->json([
            'data' => [
                'total_brands' => Brand::count(),
                'total_models' => CarModel::count(),
                'total_generations' => Generation::count(),
                'total_engines' => Engine::count(),
                'total_cars' => Car::count(),
                'published_cars' => Car::where('status', CarStatus::Published)->count(),
                'draft_cars' => Car::where('status', CarStatus::Draft)->count(),
                'total_images' => Image::count(),
                'cars_with_ai_analysis' => AiAnalysis::count(),
                'cars_per_month' => $months,
            ],
        ]);
    }
}
