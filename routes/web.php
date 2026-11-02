<?php

use Illuminate\Support\Facades\Route;
use App\http\Controllers\ProductController;

Route::get('/', fn () => to_route('products.index'));
Route::resource('products', ProductController::class);
Route::patch('products/{product}/quantity', [ProductController::class, 'adjustQuantity'])
    ->name('products.adjust-quantity');