<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Публичная часть
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');

// Административная часть
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Товары
    Route::get('/products', [ProductController::class, 'adminIndex'])->name('products.index');
    Route::resource('products', ProductController::class)->except(['index', 'show']);

    // Категории
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
});
