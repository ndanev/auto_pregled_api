<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandLogoRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Http\Resources\Admin\BrandResource;
use App\Models\Brand;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Brand::class);

        $brands = Brand::withCount('models')->orderBy('name')->get();

        return BrandResource::collection($brands);
    }

    public function store(StoreBrandRequest $request): JsonResponse
    {
        $this->authorize('create', Brand::class);

        $brand = Brand::create($request->validated());

        return (new BrandResource($brand))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Brand $brand): BrandResource
    {
        $this->authorize('view', $brand);

        return new BrandResource($brand->loadCount('models'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand): BrandResource
    {
        $this->authorize('update', $brand);

        $brand->update($request->validated());

        return new BrandResource($brand);
    }

    public function destroy(Brand $brand): JsonResponse
    {
        $this->authorize('delete', $brand);

        $brand->delete();

        return response()->json(status: 204);
    }

    public function updateLogo(UpdateBrandLogoRequest $request, Brand $brand, ImageUploadService $uploadService): BrandResource
    {
        $this->authorize('update', $brand);

        if ($brand->logo_path) {
            Storage::disk('public')->delete($brand->logo_path);
        }

        $path = $uploadService->processBrandLogo($request->file('logo'), $brand->id);
        $brand->update(['logo_path' => $path]);

        return new BrandResource($brand);
    }
}
