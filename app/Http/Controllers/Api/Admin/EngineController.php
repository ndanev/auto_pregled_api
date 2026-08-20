<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEngineRequest;
use App\Http\Requests\Admin\UpdateEngineRequest;
use App\Http\Resources\Admin\EngineResource;
use App\Models\Engine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EngineController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Engine::class);

        $engines = Engine::query()
            ->with('generation.model.brand')
            ->withCount('cars')
            ->when($request->integer('generation_id'), fn ($query, $generationId) => $query->where('generation_id', $generationId))
            ->orderBy('name')
            ->get();

        return EngineResource::collection($engines);
    }

    public function store(StoreEngineRequest $request): JsonResponse
    {
        $this->authorize('create', Engine::class);

        $engine = Engine::create($request->validated());

        return (new EngineResource($engine->load('generation.model.brand')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Engine $engine): EngineResource
    {
        $this->authorize('view', $engine);

        return new EngineResource(
            $engine->load('generation.model.brand')->loadCount('cars')
        );
    }

    public function update(UpdateEngineRequest $request, Engine $engine): EngineResource
    {
        $this->authorize('update', $engine);

        $engine->update($request->validated());

        return new EngineResource($engine->load('generation.model.brand'));
    }

    public function destroy(Engine $engine): JsonResponse
    {
        $this->authorize('delete', $engine);

        $engine->delete();

        return response()->json(status: 204);
    }
}
