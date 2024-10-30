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
use App\Http\Controllers\PonenciasController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\ProyectosColabController;

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


Route::get('/dashboard',[PanelController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/about', [EgresadoController::class,'showEgresado'])->name('about')->middleware(['verified','role:Egresado']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//ruta para carreras
Route::get('/carreras', [CarreraController::class, 'index'])->name('carreras.index');
Route::middleware(['auth', 'verified', 'role:Admin'])->group(function () {
    Route::get('/Gestioncarreras', [CarreraController::class, 'indexGestion'])->name('carreras.indexGestion');
    Route::get('/carreras/crear', [CarreraController::class, 'create'])->name('carreras.create');
    Route::post('/carreras', [CarreraController::class, 'store'])->name('carreras.store');
    Route::get('/carreras/{carrera}/editar', [CarreraController::class, 'edit'])->name('carreras.edit');
    Route::post('/carreras/{carrera}', [CarreraController::class, 'update'])->name('carreras.update');
    Route::delete('/carreras/{carrera}', [CarreraController::class, 'destroyImage'])->name('carreras.destroyImage');
    Route::delete('/carreras/{carrera}/delete', [CarreraController::class, 'destroy'])->name('carreras.destroy');
});

//rutas para cat anuncios
Route::middleware(['auth','verified', 'role:Admin'])->group(function (){
    Route::get('/categoria_anauncios',[CatAnuncioController::class, 'index'])->name('cat_anuncios.index');
    Route::get('/categoria_anauncios/crear',[CatAnuncioController::class, 'create'])->name('cat_anuncios.create');
    Route::post('/categoria_anauncios',[CatAnuncioController::class, 'store'])->name('cat_anuncios.store');
    Route::get('/categoria_anauncios/{catAnuncio}/editar',[CatAnuncioController::class, 'edit'])->name('cat_anuncios.edit');
    Route::put('/categoria_anauncios/{catAnuncio}',[CatAnuncioController::class, 'update'])->name('cat_anuncios.update');
    Route::delete('/categoria_anauncios/{catAnuncio}',[CatAnuncioController::class, 'destroy'])->name('cat_anuncios.destroy');
});

//rutas para anuncios
Route::middleware(['auth','verified', 'role:DirCarrera'])->group(function (){
    Route::get('/anuncios',[AnuncioController::class, 'index'])->name('anuncios.index');
    Route::get('/anuncios/crear',[AnuncioController::class, 'create'])->name('anuncios.create');
    Route::post('/anuncios',[AnuncioController::class, 'store'])->name('anuncios.store');
    Route::delete('/anuncios/{anuncio}',[AnuncioController::class, 'destroy'])->name('anuncios.destroy');
    Route::post('/anuncios/{anuncio}',[AnuncioController::class, 'update'])->name('anuncios.update');
    Route::get('/anuncios/{anuncio}/editar',[AnuncioController::class, 'edit'])->name('anuncios.edit');
    //para imagenes
    Route::delete('/imagenes/{imagen}/delete',[ImagenController::class, 'destroy'])->name('imagenes.destroyImage');

}); //para que solo los admin puedan acceder a estas rutas


//rutas para dir-carrera
Route::middleware(['auth','verified', 'role:Admin'])->group(function (){
    Route::get('/dir-carreras',[DirCarreraController::class, 'index'])->name('dir_carreras.index');
    Route::get('/dir-carreras/crear',[DirCarreraController::class, 'create'])->name('dir_carreras.create');
    Route::post('/dir-carreras',[DirCarreraController::class, 'store'])->name('dir_carrera.store');
    Route::get('/dir-carreras/{dirCarrera}/editar',[DirCarreraController::class, 'edit'])->name('dir_carreras.edit');
    Route::post('/dir-carreras/{dirCarrera}',[DirCarreraController::class, 'update'])->name('dir_carreras.update');
    Route::delete('dir_Carrera/{dirCarrera}',[DirCarreraController::class,'destroy'])->name('dir_carreras.destroy');
    
});

Route::get('MiPerfil',[DirCarreraController::class, 'MiPerfil'])->middleware(['auth', 'verified','role:DirCarrera'])->name('dir_carreras.MiPerfil');
Route::post('ActualizarMiPerfil',[DirCarreraController::class, 'ActualizarMiPerfil'])->middleware(['auth', 'verified','role:DirCarrera'])->name('dir_carreras.ActualizarMiPerfil');

//rutas crud egresados1
Route::get('/egresados',[EgresadoController::class, 'index'])->name('egresados.index')->middleware(['auth', 'verified','role:DirCarrera']);
Route::get('/egresados/crear',[EgresadoController::class, 'create'])->name('egresados.create')->middleware(['auth', 'verified','role:DirCarrera']); 
Route::post('/egresados/subir',[EgresadoController::class, 'store'])->name('egresados.store')->middleware(['auth', 'verified','role:DirCarrera']);
Route::delete('/egresados/{egresado}',[EgresadoController::class, 'destroy'])->name('egresado.destroy')->middleware(['auth', 'verified','role:DirCarrera']);
Route::get('/egresados/{egresado}/editar',[EgresadoController::class, 'edit'])->name('egresados.edit')->middleware(['auth', 'verified','role:DirCarrera']);
Route::put('/egresados/{egresado}',[EgresadoController::class, 'update'])->name('egresados.update')->middleware(['auth', 'verified','role:DirCarrera']);

Route::post('EditaraMiPerfil/{egresado}',[EgresadoController::class, 'EditaraMiPerfil'])->name('egresados.EditaraMiPerfil');


//ruta para ofertaTrabajo
Route::get('/ofertasTrabajo',[OfertaTrabajoController::class, 'index'])->name('ofertasTrabajo.index')->middleware(['auth', 'verified','role:DirCarrera']);;
Route::get('/ofertasTrabajo/crear',[OfertaTrabajoController::class, 'create'])->name('ofertasTrabajo.create');
Route::post('/ofertasTrabajo',[OfertaTrabajoController::class, 'store'])->name('ofertasTrabajo.store'); 
Route::get('/ofertasTrabajo/{ofertaTrabajo}/editar',[OfertaTrabajoController::class, 'edit'])->name('ofertasTrabajo.edit');
Route::post('/ofertasTrabajo/{ofertaTrabajo}',[OfertaTrabajoController::class, 'update'])->name('ofertasTrabajo.update');
Route::delete('/ofertasTrabajo/{ofertaTrabajo}',[OfertaTrabajoController::class, 'destroy'])->name('ofertasTrabajo.destroy');
Route::delete('/imagenes/{imagen}',[ImagenController::class, 'destroyImageOferta'])->name('ofertasTrabajo.destroyImageOferta');

Route::get('/EnvioCV/{ofertaTrabajo}',[OfertaTrabajoController::class, 'formularioCV'])->name('ofertasTrabajo.FormularioCV');
Route::post('/EnvioCV',[OfertaTrabajoController::class, 'EnvioCV'])->middleware(['auth', 'verified','role:Egresado'])->name('ofertasTrabajo.EnvioCV');


//rutas para paneles
Route::get('/PanelNoticias',[PanelController::class, 'Noticias'])->middleware(['auth', 'verified'])->name('Panel.Noticias');
Route::get('/PanelNoticias/{Categoria}',[PanelController::class, 'NoticiasCategoriaSelc'])->middleware(['auth', 'verified'])->name('Panel.CategoriaNoticias');

//Panel de oferta de trabajo
Route::get('/Ofertas de Trabajo',[PanelController::class, 'OfertasTrabajo'])->middleware(['auth', 'verified'])->name('Ofertas.OfertasTrabajo');
Route::get('/Ofertas de Trabajo/{Categoria}',[PanelController::class, 'OfertasTrabajoSelect'])->middleware(['auth', 'verified'])->name('Ofertas.CategoriaOfertasTrabajo');


//rutas para ver los cv de ofertas
Route::get('/CVsOfertas',[OfertaTrabajoController::class, 'CVsOfertas'])->middleware(['auth', 'verified'])->name('CVsOfertas.VerCvs');
Route::get('/GestionOfertas',[OfertaTrabajoController::class, 'GestionOfertas'])->middleware(['auth', 'verified','role:Representante'])->name('CVsOfertas.GestionOfertas');


//rutas para ponencias
Route::get('/Ponencias',[PonenciasController::class, 'index'])->name('Ponencias.index')->middleware(['auth', 'verified','role:DirCarrera']);
Route::get('/Ponencias/ProgramarPonencias',[PonenciasController::class, 'create'])->name('Ponencias.create')->middleware(['auth', 'verified','role:DirCarrera']);
Route::post('/Ponencias',[PonenciasController::class, 'store'])->name('Ponencias.store')->middleware(['auth', 'verified','role:DirCarrera']);
Route::get('/MisInvitaciones',[PonenciasController::class, 'PonenciaUsuario'])->name('Ponencias.MisInvitaciones');
Route::get('/AceptarPonencia/{ponencia}',[PonenciasController::class, 'AceptarPonencia'])->name('Ponencias.AceptarPonencia');
Route::post('/Confirmacion',[PonenciasController::class, 'Confirmacion'])->name('Ponencias.Confirmacion');
Route::get('/VerMenesaje/{AceptacionPonencia}/Aceptar',[PonenciasController::class, 'VerMenesaje'])->name('Ponencias.VerMenesaje');
Route::post('/RechazarPonencia',[PonenciasController::class, 'RechazarPonencia'])->name('Ponencias.RechazarPonencia');
//api notificaiones:
Route::get('/notificaciones', [NotificacionesController::class, 'index'])->middleware(['auth', 'verified']);


//para generar pdf
Route::get('/Reconocimiento/{id}',[PonenciasController::class, 'Reconocimiento'])->name('Ponencias.Reconocimiento')->middleware(['auth', 'verified']);;

//para proyectosColab
Route::get('/ProyectosColab',[ProyectosColabController::class, 'index'])->name('ProyectosColab.index');
Route::get('/ProyectosColab/crear',[ProyectosColabController::class, 'create'])->name('ProyectosColab.create');
Route::post('/ProyectosColab',[ProyectosColabController::class, 'store'])->name('ProyectosColab.store');
Route::get('/ListaDeProyectosColaborativos',[ProyectosColabController::class, 'PanelProyectos'])->name('ProyectosColab.PanelProyectos');
Route::get('/LisaDeProyectosColaborativos/{idCarrera}',[ProyectosColabController::class, 'PanelProyectosCarrera'])->name('ProyectosColab.PanelCarrera');


Route::get('/EnviarSolicitud/{proyecto}',[ProyectosColabController::class, 'EnviarSolicitud'])->name('ProyectosColab.EnviarSolicitud');
Route::post('/EnviarCvProyecto',[ProyectosColabController::class, 'EnviarCvProyecto'])->name('ProyectosColab.EnviarCvProyecto'); 
Route::get('/VerSolicitudes',[ProyectosColabController::class, 'VerSolicitudes'])->name('ProyectosColab.VerSolicitudes');  
Route::get('/VerCV/{id}',[ProyectosColabController::class, 'show'])->name('ProyectosColab.show'); 
Route::get('EditarProyecto/{proyecto}',[ProyectosColabController::class, 'edit'])->name('ProyectosColab.edit');
Route::post('EditarProyecto/{proyecto}',[ProyectosColabController::class, 'update'])->name('ProyectosColab.update');
Route::delete('EliminarProyecto/{proyecto}',[ProyectosColabController::class, 'destroy'])->name('ProyectosColab.destroy');
Route::delete('ImageneProyectos/{imagen}',[ImagenController::class, 'destroyImagenProyecto'])->name('ProyectosColab.destroyImagenProyecto');
Route::post('ReponderSolicitud',[ProyectosColabController::class, 'ReponderSolicitud'])->name('ProyectosColab.ReponderSolicitud');


//para mandar crear pdf
Route::get('ReporteEgresados',[EgresadoController::class, 'ReporteEgresados'])->name('Egresados.ReporteEgresados');
Route::get('ReporteCarreras',[CarreraController::class, 'ReporteCarreras'])->name('Carreras.ReporteCarreras');
Route::get('ReportePonencias',[DirCarreraController::class, 'ReportePonencias'])->name('Ponencias.ReportePonencias');

//Dashboard
Route::get('DashBoardDirector',[PanelController::class, 'DashBoardDirector'])->name('Panel.DashBoardDirector');

Route::post('/Restaurar', [PanelController::class, 'RestaurarDB'])->middleware(['auth'])->name('ResturacionDB');

Route::Get('/Respaldo', [PanelController::class, 'RespaldoDB'])->middleware(['auth'])->name('RespaldoDB');


//busquedas:
Route::post('/BuscarCarreras',[CarreraController::class, 'Buscar'])->middleware(['auth', 'verified'])->name('Carreras.Buscar');

require __DIR__.'/auth.php';
