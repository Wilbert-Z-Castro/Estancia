<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Anuncio;
use App\Models\CatAnuncio;
use App\Models\OfertaTrabajo;
use App\Models\ofertaCarrera;
use App\Models\Egresado;
use App\Models\Carrera;
use App\Models\User;
use App\Models\DirCarreta;
use App\Models\AceptacionPonencia;
use App\Models\Ponencia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Importar Log para registrar errores

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class PanelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

    }
    public function Token(){
        $token = Auth::user()->createToken('Token-user')->plainTextToken;
        return response()->json(['token' => $token]);
    }

    public function dashboard()
    {
        //
        switch (Auth::user()->Rol) {
            case 'Egresado':
                return redirect()->route('Panel.Noticias');
            case 'DirCarrera':
                return redirect()->route('egresados.index');
            case 'Representante':
                    return redirect()->route('CVsOfertas.GestionOfertas');
            case 'Alumno':
                return redirect()->route('ProyectosColab.PanelProyectos');
            default:
                return Inertia::render('Dashboard');
        }
        return Inertia::render('Dashboard');
    }

    public function Noticias()
    {
        //
        $anuncios = Anuncio::selectRaw('*, DATE_FORMAT(created_at, "%d/%m/%Y") as formatted_created_at')
        ->with(['categoria', 'imagenes' => function ($query) {
            $query->select('id_relacion', 'URL');
        }])
        ->orderBy('created_at', 'desc') 
        ->get();
        $categorias = CatAnuncio::all();
        return Inertia::render('Pages_Paneles/Noticias',[
            'anuncios' => $anuncios,
            'categorias' => $categorias,
        ]);   
    }
    public function NoticiasCategoriaSelc(string $id)
    {
        //
        
        $anuncios = Anuncio::selectRaw('*, DATE_FORMAT(created_at, "%d/%m/%Y") as formatted_created_at')
        ->with(['categoria', 'imagenes' => function ($query) {
            $query->select('id_relacion', 'URL');
        }])
        ->where('Categoria', $id)
        ->orderBy('created_at', 'desc') 
        ->get();
        $categorias = CatAnuncio::all();
        return Inertia::render('Pages_Paneles/Noticias',[
            'anuncios' => $anuncios,
            'categorias' => $categorias,
        ]);  
    }


    public function OfertasTrabajo(){

        $carreraUser = Egresado::where('Id_user', Auth::user()->id)->first()->carrera;        
        $ofertas = OfertaTrabajo::selectRaw('oferta_trabajo.*, DATE_FORMAT(oferta_trabajo.created_at, "%d/%m/%Y") as formatted_created_at')
        ->with('ofertasCarreras.carrera:idCarrera,NombreCarrera')
        ->join('ofertacarrera', 'oferta_trabajo.idOfertaTrabajo', '=', 'ofertacarrera.idoferta')
        ->join('carrera', 'ofertacarrera.idcarrera', '=', 'carrera.idCarrera')
        ->orderBy('oferta_trabajo.created_at', 'desc') 
        ->get();
        $carreras = Carrera::select('idCarrera','NombreCarrera')->get();

        return Inertia::render('Pages_Paneles/OferTrabajos',[
            'ofertas' => $ofertas,
            'carreras' => $carreras,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

     public function OfertasTrabajoSelect(string $id){

        $ofertas = OfertaTrabajo::selectRaw('oferta_trabajo.*, DATE_FORMAT(oferta_trabajo.created_at, "%d/%m/%Y") as formatted_created_at')
        ->with('ofertasCarreras.carrera:idCarrera,NombreCarrera')
        ->join('ofertacarrera', 'oferta_trabajo.idOfertaTrabajo', '=', 'ofertacarrera.idoferta')
        ->join('carrera', 'ofertacarrera.idcarrera', '=', 'carrera.idCarrera')
        ->orderBy('oferta_trabajo.created_at', 'desc') 
        ->where('carrera.idCarrera', $id)
        ->get();
        $carreras = Carrera::select('idCarrera','NombreCarrera')->get();

        return Inertia::render('Pages_Paneles/OferTrabajos',[
            'ofertas' => $ofertas,
            'carreras' => $carreras,
        ]);
    }


    public function DashBoardDirector(){
        return Inertia::render('Pages_Dashboard/DashboardDir');
    }

    public function GraficaNEgresados( ){
        $egresados = Egresado::with(['user:id', 'carrera:idCarrera,NombreCarrera,id_DirCarrera'])
        ->select('Carrera',DB::raw('COUNT(idEgresado) as total'))
        ->join('carrera', 'egresado.Carrera', '=', 'carrera.idCarrera')
        ->where('carrera.id_DirCarrera', Auth::user()->dirCarrera->idDirCarrera)
        ->groupBy('Carrera')
        ->get();
        
        //egresados por año de egreso y carrrea:
        $egresadosanios = Egresado::select('Carrera','AnioEgreso','carrera.NombreCarrera',DB::raw('COUNT(idEgresado) as total'))
        ->join('carrera', 'egresado.Carrera', '=', 'carrera.idCarrera')
        ->where('carrera.id_DirCarrera', Auth::user()->dirCarrera->idDirCarrera)
        ->groupby('AnioEgreso','Carrera','carrera.NombreCarrera')
        ->get();

        $puestoDetrabajos = Egresado::select('PuestoTrabajo','carrera.NombreCarrera',DB::raw('COUNT(idEgresado) as total'))
        ->join('carrera', 'egresado.Carrera', '=', 'carrera.idCarrera')
        ->where('carrera.id_DirCarrera', Auth::user()->dirCarrera->idDirCarrera)
        ->groupby('PuestoTrabajo','carrera.NombreCarrera')
        ->orderby('carrera.NombreCarrera')
        ->get();

        $anuncios = Anuncio::select('Nombre',DB::raw('COUNT(idCatAnuncio) as total'))
        ->join('cat_anuncio', 'anuncio.Categoria', '=', 'cat_anuncio.idCatAnuncio')
        ->where('anuncio.Id_userCreado', Auth::user()->id)
        ->groupby('Nombre')
        ->get();

        $TopPonencias = AceptacionPonencia::join('ponencias', 'aceptacion_ponencia.Id_Ponencia', '=', 'ponencias.idPonencias')
        ->join('egresado', 'aceptacion_ponencia.id_Egresado', '=', 'egresado.idEgresado')
        ->join('users', 'egresado.Id_user', '=', 'users.id')
        ->select('egresado.idEgresado','egresado.Id_user','users.name','users.ApellidoP','users.ApellidoM',DB::raw('COUNT(aceptacion_ponencia.idAceptacionPonencia) as total'))
        ->where('aceptacion_ponencia.Estado', 'Terminado')
        ->where('ponencias.Id_DirCarrera', Auth::user()->dirCarrera->idDirCarrera)
        ->whereYear('ponencias.created_at', '=', Carbon::now()->year)
        ->groupBy('egresado.idEgresado','egresado.Id_user','users.name','users.ApellidoP','users.ApellidoM','users.Telefono','users.Sexo')
        ->orderBy('total','desc')
        ->limit(5)
        ->get();



        return response()->json(
            [
            'egresados'=>$egresados,
            'egresadosanios'=>$egresadosanios,
            'puestoDetrabajos'=>$puestoDetrabajos,
            'anuncios'=>$anuncios,
            'TopPonencias'=>$TopPonencias,
            ]
        );
         /**
         * $data = $egresados->pluck('Carrera')->all();
         * 
         */
    }

    public function GraficaAnunciosPonencias(Request $request){
        $request->validate([
            'fecha_inicio' => 'required|date', // Valida que fecha_inicio sea una fecha válida
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio' // Valida que fecha_fin sea una fecha y mayor o igual a fecha_inicio
        ]);


        $fechaInicio = Carbon::parse($request->input('fecha_inicio'));
        $fechaFin = Carbon::parse($request->input('fecha_fin'));

        $anuncios = Anuncio::select('Nombre',DB::raw('COUNT(idCatAnuncio) as total'))
        ->join('cat_anuncio', 'anuncio.Categoria', '=', 'cat_anuncio.idCatAnuncio')
        ->where('anuncio.Id_userCreado', Auth::user()->id)
        ->whereBetween('anuncio.created_at', [$fechaInicio, $fechaFin])
        ->groupby('Nombre')
        ->get();
        return response()->json(
            [
            'anuncios'=>$anuncios,
            ]
        );
    }

    public function GraficaPonencias(Request $request){
        $request->validate([
            'fecha_inicioP' => 'required|date', // Valida que fecha_inicio sea una fecha válida
            'fecha_finP' => 'required|date|after_or_equal:fecha_inicio' // Valida que fecha_fin sea una fecha y mayor o igual a fecha_inicio
        ]);
        $TopPonencias = AceptacionPonencia::join('ponencias', 'aceptacion_ponencia.Id_Ponencia', '=', 'ponencias.idPonencias')
        ->join('egresado', 'aceptacion_ponencia.id_Egresado', '=', 'egresado.idEgresado')
        ->join('users', 'egresado.Id_user', '=', 'users.id')
        ->select('egresado.idEgresado','egresado.Id_user','users.name','users.ApellidoP','users.ApellidoM',DB::raw('COUNT(aceptacion_ponencia.idAceptacionPonencia) as total'))
        ->where('aceptacion_ponencia.Estado', 'Terminado')
        ->where('ponencias.Id_DirCarrera', Auth::user()->dirCarrera->idDirCarrera)
        ->whereBetween('ponencias.Fecha', [$request->input('fecha_inicioP'), $request->input('fecha_finP')])
        ->groupBy('egresado.idEgresado','egresado.Id_user','users.name','users.ApellidoP','users.ApellidoM','users.Telefono','users.Sexo')
        ->orderBy('total','desc')
        ->limit(5)
        ->get();
        return response()->json(
            [
            'anuncios'=>$TopPonencias,
            ]
        );
    }

    public function RestaurarDB(Request $request){
        $request->validate([
            'imagenes' => 'required|file|mimetypes:text/plain', // Verifica que sea un archivo de texto
        ], [
            'imagenes.required' => 'El archivo es requerido',
            'imagenes.file' => 'El archivo debe ser un archivo',
            'imagenes.mimetypes' => 'El archivo debe ser de tipo SQL',
        ]);
        $archivo = $request->file('imagenes');

        // Validar la extensión
        $extension = $archivo->getClientOriginalExtension();
        if ($extension !== 'sql') {
            return redirect()->back()->withErrors(['error' => 'El archio debe ser de tipo SQL.']);
        }

        if ($request->hasFile('imagenes')) {
            // Guardar el archivo SQL en la carpeta temporal de storage
            $archivo = $request->file('imagenes')->storeAs('temp', 'import.sql');
            $sqlPath = storage_path('app/' . $archivo);
    
            // Lee el archivo SQL
            $sqlContent = file_get_contents($sqlPath);
            if ($sqlContent === false) {
                return response()->json(['error' => 'No se pudo leer el archivo SQL.'], 500);
            }
    
            // Ejecuta las sentencias SQL
            try {
                DB::unprepared($sqlContent);
                return redirect()->route('dashboard')->with('message', 'Base de datos restaurada correctamente.');
            } catch (\Exception $e) {
                // Registro de error
                Log::error('Error al restaurar la base de datos: ' . $e->getMessage());
                return response()->json(['error' => 'Error al restaurar la base de datos: ' . $e->getMessage()], 500);
            }
        } else {
            return redirect()->back()->withErrors(['error' => 'No se ha proporcionado ningún archivo para la restauración.']);
        }


    }

    public function RespaldoDB(Request $request){
        if(function_exists('shell_exec')) {
            // Ruta donde se guardará el archivo temporalmente antes de la descarga
            $fechaHora = date('d-m-Y_H-i-s'); // Obtiene la fecha y hora actual en el formato 'YYYY-MM-DD_HH-MM-SS'
            $archivoTemporal = 'backup_' . $fechaHora . '.sql'; // Concatena la fecha y hora al nombre del archivo
            // Obtener los valores del archivo .env
            $databaseHost = env('DB_HOST');
            $databaseUsername = env('DB_USERNAME');
            $databaseName = env('DB_DATABASE');
            // Construir el comando mysqldump
            $comando = "C:/xampp/mysql/bin/mysqldump -h $databaseHost -u $databaseUsername $databaseName > $archivoTemporal";
            shell_exec($comando);
            // Obtener el contenido del archivo temporal
            $contenido = file_get_contents($archivoTemporal);
            // Descargar el archivo
            $response = response($contenido, 200);
            $response->header('Content-Type', 'application/octet-stream');
            $response->header('Content-Disposition', 'attachment; filename=' . $archivoTemporal);
            // Borrar el archivo temporal después de la descarga
            unlink($archivoTemporal);
            return $response;
        }
    }

    public function UsuariosGrafica(){
        $usuariosPorRol=User::select('Rol',DB::raw('COUNT(id) as total'))
        ->groupby('Rol')
        ->get();
        $egresadosCarrea=Egresado::with(['carrera:idCarrera,NombreCarrera'])
        ->select('Carrera',DB::raw('COUNT(idEgresado) as total'))
        ->groupby('Carrera')
        ->get();
        $anioEgreso=Egresado::select('AnioEgreso',DB::raw('COUNT(idEgresado) as total'))
        ->groupby('AnioEgreso')
        ->get();
        $TrabajoPorCarrera=ofertaCarrera::with('carrera:idCarrera,NombreCarrera')
        ->select('idcarrera',DB::raw('COUNT(idcarrera) as total'))
        ->groupby('idcarrera')
        ->get();

        
        
        return response()->json(
            [
            'usuariosPorRol'=>$usuariosPorRol,
            'egresados'=>$egresadosCarrea,
            'anioEgreso'=>$anioEgreso,
            'TrabajoPorCarrera'=>$TrabajoPorCarrera,
            ]
        );
        
    }


}
