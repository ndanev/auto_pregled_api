<?php

namespace App\Http\Controllers\Public;

use App\Enums\CarStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Public\CarDetailResource;
use App\Http\Resources\Public\CarSummaryResource;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CarController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $search = $request->string('q')->trim()->toString();
        $words = $search !== '' ? array_filter(preg_split('/\s+/', $search) ?: []) : [];
        $brandSlug = $request->string('brand_slug')->toString();
        $modelSlug = $request->string('model_slug')->toString();

        $cars = Car::query()
            ->where('status', CarStatus::Published)
            ->with(['generation.model.brand', 'engine', 'aiAnalysis', 'images'])
            ->when($brandSlug !== '', fn ($query) => $query->whereHas(
                'generation.model.brand',
                fn ($q) => $q->where('slug', $brandSlug)
            ))
            ->when($modelSlug !== '', fn ($query) => $query->whereHas(
                'generation.model',
                fn ($q) => $q->where('slug', $modelSlug)
            ))
            ->when(! empty($words), function ($query) use ($words) {
                foreach ($words as $word) {
                    $query->where(function ($subQuery) use ($word) {
                        $subQuery->whereHas('generation.model.brand', fn ($q) => $q->where('name', 'like', "%{$word}%"))
                            ->orWhereHas('generation.model', fn ($q) => $q->where('name', 'like', "%{$word}%"))
                            ->orWhereHas('generation', fn ($q) => $q->where('name', 'like', "%{$word}%"))
                            ->orWhereHas('engine', fn ($q) => $q->where('name', 'like', "%{$word}%"));
                    });
                }
            })
            ->latest()
            ->get();

        return CarSummaryResource::collection($cars);
    }

    public function show(string $slug): CarDetailResource
    {
        $car = Car::query()
            ->where('slug', $slug)
            ->where('status', CarStatus::Published)
            ->with(['generation.model.brand', 'engine', 'aiAnalysis', 'images'])
            ->firstOrFail();

        return new CarDetailResource($car);
    }
}
