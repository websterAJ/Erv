<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/banner', [App\Http\Controllers\WebController::class, 'index']);
Route::get('/blog', [App\Http\Controllers\WebController::class, 'index']);
Route::get('/galeria', [App\Http\Controllers\WebController::class, 'index']);
Route::get('/eventos', [App\Http\Controllers\WebController::class, 'index']);
Route::get('/contacto', [App\Http\Controllers\WebController::class, 'index']);
Route::get('/productos', [App\Http\Controllers\IntendenciaController::class, 'index']);
Route::get('/categorias', [App\Http\Controllers\IntendenciaController::class, 'index']);

Route::prefix('carrito')->group(function () {
    Route::get('/', [App\Http\Controllers\IntendenciaController::class, 'getCarrito']);
    Route::post('/add', [App\Http\Controllers\IntendenciaController::class, 'addProducto']);
    Route::post('/', [App\Http\Controllers\IntendenciaController::class, 'createCarrito']);
});

Route::prefix('pedido')->group(function () {
    Route::get('/', [App\Http\Controllers\IntendenciaController::class, 'getPedido']);
    Route::post('/', [App\Http\Controllers\IntendenciaController::class, 'createPedido']);
});

Route::prefix('factura')->group(function () {
    Route::get('/', [App\Http\Controllers\IntendenciaController::class, 'getFactura']);
    Route::post('/', [App\Http\Controllers\IntendenciaController::class, 'createFactura']);
});

Route::prefix('pago')->group(function () {
    Route::get('/', [App\Http\Controllers\IntendenciaController::class, 'getPago']);
    Route::post('/', [App\Http\Controllers\IntendenciaController::class, 'createPago']);
});
