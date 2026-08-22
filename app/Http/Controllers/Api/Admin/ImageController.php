<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReorderImagesRequest;
use App\Http\Requests\Admin\StoreImagesRequest;
use App\Http\Requests\Admin\UpdateImageRequest;
use App\Http\Resources\Admin\ImageResource;
use App\Models\Car;
use App\Models\Image;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ImageController extends Controller
{
    public function __construct(
        private readonly ImageUploadService $uploadService,
    ) {}

    public function index(Car $car): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Image::class);

        return ImageResource::collection(
            $car->images()->orderBy('sort_order')->get()
        );
    }

    public function store(StoreImagesRequest $request, Car $car): AnonymousResourceCollection
    {
        $this->authorize('create', Image::class);

        $hasExistingImages = $car->images()->exists();
        $nextSortOrder = (int) $car->images()->max('sort_order') + 1;

        $created = collect($request->file('images'))->map(function ($file, $index) use ($car, &$nextSortOrder, $hasExistingImages) {
            $path = $this->uploadService->process($file, $car->id);

            return $car->images()->create([
                'path' => $path,
                'is_main' => ! $hasExistingImages && $index === 0,
                'sort_order' => $nextSortOrder++,
            ]);
        });

        return ImageResource::collection($created);
    }

    public function update(UpdateImageRequest $request, Image $image): ImageResource
    {
        $this->authorize('update', $image);

        if ($request->boolean('is_main')) {
            $image->car->images()->where('id', '!=', $image->id)->update(['is_main' => false]);
        }

        $image->update(['is_main' => $request->boolean('is_main')]);

        return new ImageResource($image);
    }

    public function reorder(ReorderImagesRequest $request, Car $car): JsonResponse
    {
        $this->authorize('update', $car);

        foreach ($request->validated('image_ids') as $index => $imageId) {
            $car->images()->where('id', $imageId)->update(['sort_order' => $index]);
        }

        return response()->json(['message' => 'Redosled je ažuriran.']);
    }

    public function destroy(Image $image): JsonResponse
    {
        $this->authorize('delete', $image);

        $this->uploadService->delete($image->path);
        $image->delete();

        return response()->json(status: 204);
    }
}
