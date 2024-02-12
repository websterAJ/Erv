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
