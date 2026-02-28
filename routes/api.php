<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;


Route::get('/products', [ProductController::class, 'index']);


Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store'])->middleware('rate.limit.create_orders');;
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::patch('/{id}/status', [OrderController::class, 'updateStatus']);
});