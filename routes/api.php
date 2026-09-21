<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductTypeController;
use App\Http\Controllers\Api\PublicCatalogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {

    /*
    | Catalog
    */
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    /*
    | Master Data - Read
    */
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/brands/{brand}', [BrandController::class, 'show']);

    Route::get('/product-types', [ProductTypeController::class, 'index']);
    Route::get('/product-types/{productType}', [ProductTypeController::class, 'show']);

    /*
    | Owner Management
    */
    Route::middleware('owner')->group(function () {

        // Products
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        // Categories
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        // Brands
        Route::post('/brands', [BrandController::class, 'store']);
        Route::put('/brands/{brand}', [BrandController::class, 'update']);
        Route::delete('/brands/{brand}', [BrandController::class, 'destroy']);

        // Product Types
        Route::post('/product-types', [ProductTypeController::class, 'store']);
        Route::put('/product-types/{productType}', [ProductTypeController::class, 'update']);
        Route::delete('/product-types/{productType}', [ProductTypeController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| Public Catalog
|--------------------------------------------------------------------------
*/

Route::prefix('public')->group(function () {

    Route::get(
        '/stores/{tenant:slug}/products',
        [PublicCatalogController::class, 'products']
    );

    Route::get(
        '/stores/{tenant:slug}/products/{product}',
        [PublicCatalogController::class, 'product']
    );
});
