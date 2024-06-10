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
Route::get('/perfil', [App\Http\Controllers\UserController::class, 'perfil'])->name('perfil');

Route::prefix('cargos')->group(function () {
    Route::get('/', [App\Http\Controllers\CargosController::class, 'index']);
    Route::get('/create', [App\Http\Controllers\CargosController::class, 'create']);
    Route::get('/edit/{$id}', [App\Http\Controllers\CargosController::class, 'edit']);
    Route::post('/create', [App\Http\Controllers\CargosController::class, 'store']);
    Route::post('/delete/{$id}', [App\Http\Controllers\CargosController::class, 'delete']);
    Route::post('/update', [App\Http\Controllers\CargosController::class, 'update']);
});

Route::prefix('directiva')->group(function () {
    Route::get('/', [App\Http\Controllers\directivasController::class, 'index']);
    Route::get('/create', [App\Http\Controllers\directivasController::class, 'create']);
    Route::get('/edit/{$id}', [App\Http\Controllers\directivasController::class, 'edit']);
    Route::post('/create', [App\Http\Controllers\directivasController::class, 'store']);
    Route::post('/delete/{$id}', [App\Http\Controllers\directivasController::class, 'delete']);
    Route::post('/update', [App\Http\Controllers\directivasController::class, 'update']);
});


Route::prefix('web')->group(function () {
    Route::get('/', [App\Http\Controllers\WebController::class, 'show']);
    Route::get('/banner', [App\Http\Controllers\WebController::class, 'show']);
    Route::get('/blog', [App\Http\Controllers\WebController::class, 'show']);
    Route::get('/evento', [App\Http\Controllers\WebController::class, 'show']);
    Route::get('/contactonos', [App\Http\Controllers\WebController::class, 'show']);

    Route::get('/create/{tabla}', [App\Http\Controllers\WebController::class, 'create']);
    Route::post('/create/{tabla}', [App\Http\Controllers\WebController::class, 'store']);
    Route::get('/resumen', [App\Http\Controllers\WebController::class, 'show']);
    Route::get('/reporte', [App\Http\Controllers\WebController::class, 'show']);
    Route::post('/', [App\Http\Controllers\WebController::class, 'create']);
    Route::post('/update', [App\Http\Controllers\WebController::class, 'update']);
});

Route::prefix('intendencia')->group(function () {
    Route::get('/producto', [App\Http\Controllers\IntendenciaController::class, 'show']);
    Route::get('/pedido', [App\Http\Controllers\IntendenciaController::class, 'show']);
    Route::get('/resumen', [App\Http\Controllers\IntendenciaController::class, 'show']);
    Route::get('/reporte', [App\Http\Controllers\IntendenciaController::class, 'show']);
    Route::get('/create/{tabla}', [App\Http\Controllers\IntendenciaController::class, 'create']);
    Route::post('/create/{tabla}', [App\Http\Controllers\IntendenciaController::class, 'store']);
    Route::post('/', [App\Http\Controllers\IntendenciaController::class, 'create']);
    Route::post('/update', [App\Http\Controllers\IntendenciaController::class, 'update']);
});

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
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'show']);
        Route::get('/registro', [App\Http\Controllers\UserController::class, 'create']);
        Route::post('/registro', [App\Http\Controllers\UserController::class, 'store']);
        Route::post('/update', [App\Http\Controllers\UserController::class, 'update']);
        Route::post('/delete', [App\Http\Controllers\UserController::class, 'delete']);
    });
    Route::get('/usuarios', [App\Http\Controllers\UserController::class, 'show'])->name('usuarios');
    Route::get('/permisos', [App\Http\Controllers\PermisosController::class, 'show'])->name('permisos');
    Route::get('/roles', [App\Http\Controllers\RolesController::class, 'show'])->name('roles');
    Route::get('/config', [App\Http\Controllers\ConfigController::class, 'show'])->name('config');
    Route::post('/config', [App\Http\Controllers\ConfigController::class, 'store']);
});
