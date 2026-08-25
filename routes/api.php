<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\BrandController;
use App\Http\Controllers\Api\Admin\CarAiAnalysisController;
use App\Http\Controllers\Api\Admin\CarController;
use App\Http\Controllers\Api\Admin\CarModelController;
use App\Http\Controllers\Api\Admin\EngineController;
use App\Http\Controllers\Api\Admin\GenerationController;
use App\Http\Controllers\Api\Admin\ImageController;
use App\Http\Controllers\Public\CarController as PublicCarController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::post('cars/{car}/images', [ImageController::class, 'store']);
        Route::get('cars/{car}/images', [ImageController::class, 'index']);
        Route::post('cars/{car}/images/reorder', [ImageController::class, 'reorder']);
        Route::post('cars/{car}/generate-analysis', [CarAiAnalysisController::class, 'generate']);

        Route::put('images/{image}', [ImageController::class, 'update']);
        Route::delete('images/{image}', [ImageController::class, 'destroy']);

        Route::apiResource('brands', BrandController::class);
        Route::apiResource('models', CarModelController::class);
        Route::apiResource('generations', GenerationController::class);
        Route::apiResource('engines', EngineController::class);
        Route::apiResource('cars', CarController::class);
    });
});

Route::get('cars', [PublicCarController::class, 'index']);
Route::get('cars/{slug}', [PublicCarController::class, 'show']);