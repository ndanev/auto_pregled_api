<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreModelRequest;
use App\Http\Requests\Admin\UpdateModelRequest;
use App\Http\Resources\Admin\ModelResource;
use App\Models\CarModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CarModelController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', CarModel::class);

        $models = CarModel::query()
            ->with('brand')
            ->withCount('generations')
            ->when($request->integer('brand_id'), fn ($query, $brandId) => $query->where('brand_id', $brandId))
            ->orderBy('name')
            ->get();

        return ModelResource::collection($models);
    }

    public function store(StoreModelRequest $request): JsonResponse
    {
        $this->authorize('create', CarModel::class);

        $model = CarModel::create($request->validated());

        return (new ModelResource($model->load('brand')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(CarModel $model): ModelResource
    {
        $this->authorize('view', $model);

        return new ModelResource($model->load('brand')->loadCount('generations'));
    }

    public function update(UpdateModelRequest $request, CarModel $model): ModelResource
    {
        $this->authorize('update', $model);

        $model->update($request->validated());

        return new ModelResource($model->load('brand'));
    }

    public function destroy(CarModel $model): JsonResponse
    {
        $this->authorize('delete', $model);

        $model->delete();

        return response()->json(status: 204);
    }
}
