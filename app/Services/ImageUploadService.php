<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;

class ImageUploadService
{
    private const MAX_WIDTH = 1920;

    private const THUMBNAIL_WIDTH = 400;

    /**
     * Obrađuje upload-ovanu sliku: konvertuje u WebP, smanjuje ako je
     * prevelika, i pravi thumbnail. Vraća relativnu putanju glavne slike
     * (thumbnail putanja se izvodi konvencijom: isto ime + "-thumb").
     */
    public function process(UploadedFile $file, int $carId): string
    {
        $manager = ImageManager::usingDriver(Driver::class);
        $image = $manager->decodeSplFileInfo($file);

        $image->scaleDown(width: self::MAX_WIDTH);

        $filename = Str::random(20);
        $directory = "cars/{$carId}";

        $mainPath = "{$directory}/{$filename}.webp";
        $encoded = $image->encodeUsingFormat(Format::WEBP, quality: 85);
        Storage::disk('public')->put($mainPath, (string) $encoded);

        $thumbnail = clone $image;
        $thumbnail->scaleDown(width: self::THUMBNAIL_WIDTH);
        $thumbnailPath = "{$directory}/{$filename}-thumb.webp";
        $encodedThumbnail = $thumbnail->encodeUsingFormat(Format::WEBP, quality: 80);
        Storage::disk('public')->put($thumbnailPath, (string) $encodedThumbnail);

        return $mainPath;
    }

    /**
     * Briše glavnu sliku i njen thumbnail sa diska.
     */
    public function delete(string $path): void
    {
        Storage::disk('public')->delete([
            $path,
            $this->thumbnailPath($path),
        ]);
    }

    public function thumbnailPath(string $path): string
    {
        return preg_replace('/\.webp$/', '-thumb.webp', $path) ?? $path;
    }
}
