<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/perfil', [App\Http\Controllers\UserController::class, 'show'])->name('perfil');

Route::prefix('zonas')->group(function () {
    Route::get('/', [App\Http\Controllers\ZonasController::class, 'show']);
    Route::get('/registro', [App\Http\Controllers\ZonasController::class, 'create']);
    Route::get('/resumen', [App\Http\Controllers\ZonasController::class, 'show']);
    Route::get('/reporte', [App\Http\Controllers\ZonasController::class, 'show']);
    Route::post('/', [App\Http\Controllers\ZonasController::class, 'create']);
    Route::post('/update', [App\Http\Controllers\ZonasController::class, 'update']);
});

Route::prefix('tesoreria')->group(function () {
    Route::get('/', [App\Http\Controllers\TesoreriaController::class, 'show']);
    Route::get('/pagos', [App\Http\Controllers\TesoreriaController::class, 'showPagos']);
    Route::get('/cuota', [App\Http\Controllers\TesoreriaController::class, 'createCuota']);
    Route::post('/cuota/registro', [App\Http\Controllers\TesoreriaController::class, 'storeCuota']);
    Route::get('/registro', [App\Http\Controllers\TesoreriaController::class, 'create']);
    Route::get('/resumen', [App\Http\Controllers\TesoreriaController::class, 'show']);
    Route::get('/reporte', [App\Http\Controllers\TesoreriaController::class, 'show']);
    Route::post('/registro', [App\Http\Controllers\TesoreriaController::class, 'store']);
    Route::post('/update', [App\Http\Controllers\TesoreriaController::class, 'update']);
});

Route::prefix('admin')->group(function () {
    Route::get('/usuarios', [App\Http\Controllers\UserController::class, 'show'])->name('usuarios');
    Route::get('/permisos', [App\Http\Controllers\PermisosController::class, 'show'])->name('permisos');
    Route::get('/roles', [App\Http\Controllers\RolesController::class, 'show'])->name('roles');
    Route::get('/config', [App\Http\Controllers\ConfigController::class, 'show'])->name('config');
    Route::post('/config', [App\Http\Controllers\ConfigController::class, 'store']);
});
