<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGenerationRequest;
use App\Http\Requests\Admin\UpdateGenerationRequest;
use App\Http\Resources\Admin\GenerationResource;
use App\Models\Generation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GenerationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Generation::class);

        $generations = Generation::query()
            ->with('model.brand')
            ->withCount(['engines', 'cars'])
            ->when($request->integer('model_id'), fn ($query, $modelId) => $query->where('model_id', $modelId))
            ->orderBy('name')
            ->get();

        return GenerationResource::collection($generations);
    }

    public function store(StoreGenerationRequest $request): JsonResponse
    {
        $this->authorize('create', Generation::class);

        $generation = Generation::create($request->validated());

        return (new GenerationResource($generation->load('model.brand')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Generation $generation): GenerationResource
    {
        $this->authorize('view', $generation);

        return new GenerationResource(
            $generation->load('model.brand')->loadCount(['engines', 'cars'])
        );
    }

    public function update(UpdateGenerationRequest $request, Generation $generation): GenerationResource
    {
        $this->authorize('update', $generation);

        $generation->update($request->validated());

        return new GenerationResource($generation->load('model.brand'));
    }

    public function destroy(Generation $generation): JsonResponse
    {
        $this->authorize('delete', $generation);

        $generation->delete();

        return response()->json(status: 204);
    }
}
