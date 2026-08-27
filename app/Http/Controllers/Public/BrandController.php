<?php

namespace App\Http\Controllers\Public;

use App\Enums\CarStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Public\BrandDetailResource;
use App\Http\Resources\Public\BrandSummaryResource;
use App\Models\Brand;
use App\Models\Car;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $brands = Brand::all()->map(function (Brand $brand) {
            $brand->published_cars_count = Car::query()
                ->where('status', CarStatus::Published)
                ->whereHas('generation.model', fn ($q) => $q->where('brand_id', $brand->id))
                ->count();

            return $brand;
        })->filter(fn (Brand $brand) => $brand->published_cars_count > 0)
            ->values();

        return BrandSummaryResource::collection($brands);
    }

    public function show(string $slug): BrandDetailResource
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        $models = $brand->models()
            ->get()
            ->map(function ($model) {
                $model->published_cars_count = Car::query()
                    ->where('status', CarStatus::Published)
                    ->whereHas('generation', fn ($q) => $q->where('model_id', $model->id))
                    ->count();

                return $model;
            })
            ->filter(fn ($model) => $model->published_cars_count > 0)
            ->values();

        $brand->setRelation('models', $models);

        return new BrandDetailResource($brand);
    }
}
