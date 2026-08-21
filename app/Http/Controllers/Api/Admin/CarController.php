<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\BodyType;
use App\Enums\Transmission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCarRequest;
use App\Http\Requests\Admin\UpdateCarRequest;
use App\Http\Resources\Admin\CarResource;
use App\Models\Car;
use App\Models\Engine;
use App\Models\Generation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CarController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Car::class);

        $cars = Car::query()
            ->with(['generation.model.brand', 'engine'])
            ->withCount('images')
            ->when($request->integer('generation_id'), fn ($query, $generationId) => $query->where('generation_id', $generationId))
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->get();

        return CarResource::collection($cars);
    }

    public function store(StoreCarRequest $request): JsonResponse
    {
        $this->authorize('create', Car::class);

        $validated = $request->validated();

        $generation = Generation::findOrFail((int) $validated['generation_id']);
        $engine = Engine::findOrFail((int) $validated['engine_id']);

        $validated['slug'] = Car::generateSlug(
            $generation,
            $engine,
            Transmission::from($validated['transmission']),
            isset($validated['body_type']) ? BodyType::from($validated['body_type']) : null,
        );

        $car = Car::create($validated);

        return (new CarResource($car->load(['generation.model.brand', 'engine'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Car $car): CarResource
    {
        $this->authorize('view', $car);

        return new CarResource(
            $car->load(['generation.model.brand', 'engine', 'aiAnalysis'])->loadCount('images')
        );
    }

    public function update(UpdateCarRequest $request, Car $car): CarResource
    {
        $this->authorize('update', $car);

        // Slug se namerno NE regeneriše pri izmeni — SEO landing stranice
        // ne bi trebalo da menjaju URL nakon indeksiranja.
        $car->update($request->validated());

        return new CarResource($car->load(['generation.model.brand', 'engine']));
    }

    public function destroy(Car $car): JsonResponse
    {
        $this->authorize('delete', $car);

        $car->delete();

        return response()->json(status: 204);
    }
}
