<?php

namespace App\Http\Resources\Admin;

use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $car_id
 * @property string $path
 * @property bool $is_main
 * @property int $sort_order
 * @property Carbon $created_at
 */
class ImageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $uploadService = app(ImageUploadService::class);

        return [
            'id' => $this->id,
            'car_id' => $this->car_id,
            'url' => Storage::disk('public')->url($this->path),
            'thumbnail_url' => Storage::disk('public')->url($uploadService->thumbnailPath($this->path)),
            'is_main' => $this->is_main,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at,
        ];
    }
}
