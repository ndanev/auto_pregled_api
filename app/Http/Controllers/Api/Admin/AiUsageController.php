<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiUsageLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class AiUsageController extends Controller
{
    public function stats(): JsonResponse
    {
        $last30Days = AiUsageLog::where('created_at', '>=', Carbon::now()->subDays(30));

        $recent = AiUsageLog::with('car.generation.model.brand')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (AiUsageLog $log) => [
                'id' => $log->id,
                'car_label' => $log->car
                    ? "{$log->car->generation->model->brand->name} {$log->car->generation->model->name} {$log->car->generation->name}"
                    : 'Nepoznat automobil',
                'status' => $log->status,
                'total_tokens' => $log->total_tokens,
                'estimated_cost_usd' => $log->estimated_cost_usd,
                'created_at' => $log->created_at,
            ]);

        return response()->json([
            'data' => [
                'total_requests' => AiUsageLog::count(),
                'total_requests_30d' => (clone $last30Days)->count(),
                'total_tokens_30d' => (clone $last30Days)->sum('total_tokens'),
                'estimated_cost_30d_usd' => round((float) (clone $last30Days)->sum('estimated_cost_usd'), 2),
                'failed_requests_30d' => (clone $last30Days)->where('status', 'failed')->count(),
                'recent_requests' => $recent,
            ],
        ]);
    }
}
