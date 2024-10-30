<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\CatAnuncioController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\AnuncioController;
use App\Http\Controllers\DirCarreraController;
use App\Http\Controllers\EgresadoController;
use App\Http\Controllers\OfertaTrabajoController;
use App\Http\Controllers\PonenciasController;
use App\Http\Controllers\ProyectosColabController;
use App\Http\Controllers\CarreraController;

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

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/notificaciones', [NotificacionesController::class, 'index'])->middleware(['auth']);

//para graficas
Route::post('/GraficaNEgresados', [PanelController::class, 'GraficaNEgresados'])->middleware(['auth']);
Route::post('/GraficaAnunciosPonencias', [PanelController::class, 'GraficaAnunciosPonencias'])->middleware(['auth']);
Route::post('/GraficaPonencias', [PanelController::class, 'GraficaPonencias'])->middleware(['auth']);
Route::post('/Restaurar', [PanelController::class, 'RestaurarDB'])->middleware(['auth']);
Route::Get('/Respaldo', [PanelController::class, 'RespaldoDB'])->middleware(['auth']);

//apis para administrador
Route::get('/Usuarios',[PanelController::class, 'UsuariosGrafica'])->middleware(['auth']);
Route::get('/BuscarCategoria',[CatAnuncioController::class, 'Buscar'])->middleware(['auth']);
Route::get('/BuscarAnuncio',[AnuncioController::class, 'Buscar'])->middleware(['auth']);
Route::get('/BuscarEgresado',[EgresadoController::class, 'show'])->middleware(['auth']);
Route::get('/BuscarOferta',[OfertaTrabajoController::class, 'Buscar'])->middleware(['auth']);
Route::get('/BuscarDirCarrera',[DirCarreraController::class, 'Buscar'])->middleware(['auth']);
Route::get('/token', [PanelController::class, 'Token'])->middleware(['auth']);