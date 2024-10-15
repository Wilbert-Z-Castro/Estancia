<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificacionesController;

use App\Http\Controllers\PanelController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/notificaciones', [NotificacionesController::class, 'index'])->middleware(['auth:sanctum']);

//para graficas
Route::post('/GraficaNEgresados', [PanelController::class, 'GraficaNEgresados'])->middleware(['auth:sanctum']);
Route::post('/GraficaAnunciosPonencias', [PanelController::class, 'GraficaAnunciosPonencias'])->middleware(['auth:sanctum']);
Route::post('/GraficaPonencias', [PanelController::class, 'GraficaPonencias'])->middleware(['auth:sanctum']);
Route::post('/Restaurar', [PanelController::class, 'RestaurarDB'])->middleware(['auth:sanctum']);
Route::Get('/Respaldo', [PanelController::class, 'RespaldoDB'])->middleware(['auth:sanctum']);


Route::get('/token', [PanelController::class, 'Token'])->middleware(['auth']);