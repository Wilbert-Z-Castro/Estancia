<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\CatAnuncioController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\AnuncioController;
use App\Http\Controllers\DirCarreraController;
use App\Http\Controllers\EgresadoController;
use App\Http\Controllers\OfertaTrabajoController;
use App\Http\Controllers\PanelController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/about', fn () => Inertia::render('About'))->name('about');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//ruta para carreras
Route::get('/carreras', [CarreraController::class, 'index'])->name('carreras.index');
Route::get('/Gestioncarreras', [CarreraController::class, 'indexGestion'])->name('carreras.indexGestion');
Route::get('/carreras/crear', [CarreraController::class, 'create'])->name('carreras.create');
Route::post('/carreras', [CarreraController::class, 'store'])->name('carreras.store');
Route::get('/carreras/{carrera}/editar', [CarreraController::class, 'edit'])->name('carreras.edit');
Route::post('/carreras/{carrera}', [CarreraController::class, 'update'])->name('carreras.update');
Route::delete('/carreras/{carrera}', [CarreraController::class, 'destroyImage'])->name('carreras.destroyImage');
Route::delete('/carreras/{carrera}/delete', [CarreraController::class, 'destroy'])->name('carreras.destroy');    




//rutas para cat anuncios
Route::get('/categoria_anauncios',[CatAnuncioController::class, 'index'])->name('cat_anuncios.index');
Route::get('/categoria_anauncios/crear',[CatAnuncioController::class, 'create'])->name('cat_anuncios.create');
Route::post('/categoria_anauncios',[CatAnuncioController::class, 'store'])->name('cat_anuncios.store');
Route::get('/categoria_anauncios/{catAnuncio}/editar',[CatAnuncioController::class, 'edit'])->name('cat_anuncios.edit');
Route::put('/categoria_anauncios/{catAnuncio}',[CatAnuncioController::class, 'update'])->name('cat_anuncios.update');
Route::delete('/categoria_anauncios/{catAnuncio}',[CatAnuncioController::class, 'destroy'])->name('cat_anuncios.destroy');


//rutas para anuncios
Route::get('/anuncios',[AnuncioController::class, 'index'])->name('anuncios.index');
Route::get('/anuncios/crear',[AnuncioController::class, 'create'])->name('anuncios.create');
Route::post('/anuncios',[AnuncioController::class, 'store'])->name('anuncios.store');
Route::delete('/anuncios/{anuncio}',[AnuncioController::class, 'destroy'])->name('anuncios.destroy');
Route::post('/anuncios/{anuncio}',[AnuncioController::class, 'update'])->name('anuncios.update');
Route::get('/anuncios/{anuncio}/editar',[AnuncioController::class, 'edit'])->name('anuncios.edit');
//para imagenes
Route::delete('/imagenes/{imagen}',[ImagenController::class, 'destroy'])->name('imagenes.destroy');


//rutas para dir-carrera
Route::get('/dir-carreras',[DirCarreraController::class, 'index'])->name('dir_carreras.index');
Route::get('/dir-carreras/crear',[DirCarreraController::class, 'create'])->name('dir_carreras.create');
Route::post('/dir-carreras',[DirCarreraController::class, 'store'])->name('dir_carrera.store');
Route::get('/dir-carreras/{dirCarrera}/editar',[DirCarreraController::class, 'edit'])->name('dir_carreras.edit');
Route::put('/dir-carreras/{dirCarrera}',[DirCarreraController::class, 'update'])->name('dir_carreras.update');
Route::delete('dir_Carrera/{dirCarrera}',[DirCarreraController::class,'destroy'])->name('dir_carreras.destroy');

//rutas crud egresados
Route::get('/egresados',[EgresadoController::class, 'index'])->name('egresados.index');
Route::get('/egresados/crear',[EgresadoController::class, 'create'])->name('egresados.create'); 
Route::post('/egresados/subir',[EgresadoController::class, 'store'])->name('egresados.store');
Route::delete('/egresados/{egresado}',[EgresadoController::class, 'destroy'])->name('egresado.destroy');
Route::get('/egresados/{egresado}/editar',[EgresadoController::class, 'edit'])->name('egresados.edit');
Route::put('/egresados/{egresado}',[EgresadoController::class, 'update'])->name('egresados.update');


//ruta para ofertaTrabajo
Route::get('/ofertasTrabajo',[OfertaTrabajoController::class, 'index'])->name('ofertasTrabajo.index');
Route::get('/ofertasTrabajo/crear',[OfertaTrabajoController::class, 'create'])->name('ofertasTrabajo.create');
Route::post('/ofertasTrabajo',[OfertaTrabajoController::class, 'store'])->name('ofertasTrabajo.store'); 
Route::get('/ofertasTrabajo/{ofertaTrabajo}/editar',[OfertaTrabajoController::class, 'edit'])->name('ofertasTrabajo.edit');
Route::post('/ofertasTrabajo/{ofertaTrabajo}',[OfertaTrabajoController::class, 'update'])->name('ofertasTrabajo.update');
Route::delete('/ofertasTrabajo/{ofertaTrabajo}',[OfertaTrabajoController::class, 'destroy'])->name('ofertasTrabajo.destroy');
Route::delete('/imagenes/{imagen}',[ImagenController::class, 'destroyImageOferta'])->name('ofertasTrabajo.destroyImageOferta');


//rutas para paneles
Route::get('/PanelNoticias',[PanelController::class, 'Noticias'])->name('Panel.Noticias');
Route::get('/PanelNoticias/{Categoria}',[PanelController::class, 'NoticiasCategoriaSelc'])->name('Panel.CategoriaNoticias');
//Panel de oferta de trabajo
Route::get('/Ofertas de Trabajo',[PanelController::class, 'OfertasTrabajo'])->name('Ofertas.OfertasTrabajo');
Route::get('/Ofertas de Trabajo/{Categoria}',[PanelController::class, 'OfertasTrabajoSelect'])->name('Ofertas.CategoriaOfertasTrabajo');
require __DIR__.'/auth.php';
