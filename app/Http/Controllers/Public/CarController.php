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

        $cars = Car::query()
            ->where('status', CarStatus::Published)
            ->with(['generation.model.brand', 'engine', 'aiAnalysis', 'images'])
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('generation.model.brand', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('generation.model', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('generation', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('engine', fn ($q) => $q->where('name', 'like', "%{$search}%"));
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
