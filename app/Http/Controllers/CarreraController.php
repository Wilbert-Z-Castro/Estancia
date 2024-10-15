<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;
use App\Models\DirCarrera;
use App\Models\OfertaTrabajo;
use App\Models\Egresado;
use App\Models\CvOfertas;
use App\Models\ofertaCarrera;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;



class CarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $carreras = Carrera::all();
        return inertia('Register', [
            'carreras' => $carreras,
        ]);
    }
    public function indexGestion()
    {
        //
        $carreras = Carrera::with('dirCarrera.user')->paginate(12); 
        return inertia('Pages_Carreras/index', [
            'carreras' => $carreras,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $DirCarrera = DirCarrera::join('users', 'dir_carrera.id_userDir', '=', 'users.id')
        ->select('dir_carrera.idDirCarrera as dirCarrera_id', 'users.name as user_name')
        ->where('users.Rol', '=', 'DirCarrera')
        ->get();
        return inertia('Pages_Carreras/form', [
            'DirCarrera' => $DirCarrera,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        
        
        $request->validate([
            'NombreCarrera' => 'required|string|max:255',
            'Descripcion' => 'required|string',
            'UbicacionOficinas' => 'required|string',
            'dirCarrera_id' => 'required|integer',
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $carrera = new Carrera();
        $carrera->NombreCarrera = $request->input('NombreCarrera');
        $carrera->Descripcion = $request->input('Descripcion');
        $carrera->id_DirCarrera = $request->input('dirCarrera_id');
        $carrera->UbicacionOficinas = $request->input('UbicacionOficinas');


        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                // Generar un nombre único para el archivo
                $timestamp = now()->format('YmdHis');
                $filename = $timestamp . '_' . $file->getClientOriginalName();

                // Almacenar el archivo en el disco 'public' con el nuevo nombre
                $path = $file->storeAs('PlanDeEstudio', $filename, 'public');
                $carrera->PlanEstudios = $path;

            }
        }
        $carrera->save();
        return redirect()->route('carreras.indexGestion')->with('message', 'la carrera se creo exitosamente');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $carrera = Carrera::find($id);
        $DirCarrera = DirCarrera::join('users', 'dir_carrera.id_userDir', '=', 'users.id')
        ->select('dir_carrera.idDirCarrera as dirCarrera_id', 'users.name as user_name')
        ->where('users.Rol', '=', 'DirCarrera')
        ->get();
        return inertia('Pages_Carreras/Editar', [
            'carrera' => $carrera,
            'DirCarrera' => $DirCarrera,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'NombreCarrera' => 'required|string|max:255',
            'Descripcion' => 'required|string',
            'UbicacionOficinas' => 'required|string',
            'dirCarrera_id' => 'required|integer',
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'NombreCarrera.required' => 'El campo Nombre de la Carrera es obligatorio.',
            'NombreCarrera.string' => 'El campo Nombre de la Carrera debe ser una cadena de texto.',
            'NombreCarrera.max' => 'El campo Nombre de la Carrera no puede tener más de 255 caracteres.',
            'Descripcion.required' => 'El campo Descripción es obligatorio.',
            'Descripcion.string' => 'El campo Descripción debe ser una cadena de texto.',
            'UbicacionOficinas.required' => 'El campo Ubicación de Oficinas es obligatorio.',
            'UbicacionOficinas.string' => 'El campo Ubicación de Oficinas debe ser una cadena de texto.',
            'dirCarrera_id.required' => 'El campo Dirección de Carrera es obligatorio.',
            'dirCarrera_id.integer' => 'El campo Dirección de Carrera debe ser un de los nombre en la lista.',
            'imagenes.*.file' => 'Cada archivo debe ser una imagen válida.',
            'imagenes.*.mimes' => 'Las imágenes deben estar en formato jpeg, png, jpg o gif.',
            'imagenes.*.max' => 'Cada imagen no puede ser mayor de 2MB.',
        ]);
        $carrera = Carrera::find($id);
        $carrera->NombreCarrera = $request->input('NombreCarrera');
        $carrera->Descripcion = $request->input('Descripcion');
        $carrera->UbicacionOficinas = $request->input('UbicacionOficinas');
        $carrera->id_DirCarrera = $request->input('dirCarrera_id');
        

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                // Generar un nombre único para el archivo
                $timestamp = now()->format('YmdHis');
                $filename = $timestamp . '_' . $file->getClientOriginalName();
                if($carrera->PlanEstudios!=null){
                    Storage::disk('public')->delete($carrera->PlanEstudios);
                }
                $carrera->PlanEstudios =null;
                // Almacenar el archivo en el disco 'public' con el nuevo nombre
                $path = $file->storeAs('PlanDeEstudio', $filename, 'public');
                $carrera->PlanEstudios = $path;

            }
        }
        $carrera->save();
        return redirect()->route('carreras.indexGestion')->with('message', 'la carrera se actualizo exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $carrera = Carrera::findOrFail($id);
        if($carrera->PlanEstudios!=null){
            Storage::disk('public')->delete($carrera->PlanEstudios);
        }
        $carrera->delete();
        return redirect()->route('carreras.indexGestion')->with('message', 'la carrera se elimino exitosamente');
    }

    public function destroyImage(string $id)
    {
        //
        $carrera = Carrera::findOrFail($id);
        if (Storage::disk('public')->exists($carrera->PlanEstudios)) {
            Storage::disk('public')->delete($carrera->PlanEstudios);
        }
        $carrera->PlanEstudios =null;
        $carrera->save();
        return redirect()->back()->with('message', 'La imagen fue eliminada exitosamente');
    }

    public function ReporteCarreras(){
        $carreras = Carrera::withCount(['egresados'])
        ->where('id_DirCarrera','=',Auth()->user()->dirCarrera->idDirCarrera)
        ->get()
        ->groupby('NombreCarrera');

        $datosDirector = DirCarrera::with('user:id,name,email,ApellidoP,ApellidoM,Telefono,Sexo')
        ->where('id_userDir','=',Auth()->user()->id)
        ->first(); 

        //ofertas de trabajo a la crrera
        $ofertasCarreras = Ofertacarrera::join('carrera', 'ofertacarrera.idcarrera', '=', 'carrera.idCarrera')
        ->select(DB::raw('count(ofertacarrera.idOfertaCarrera) as total_ofertas'), 'carrera.NombreCarrera')
        ->where('carrera.id_DirCarrera', '=', Auth()->user()->dirCarrera->idDirCarrera)
        ->groupBy('carrera.NombreCarrera')
        ->get();
        $labels = $ofertasCarreras->pluck('NombreCarrera');
        $data = $ofertasCarreras->pluck('total_ofertas');
        $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode([
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Numero de ofertas registradas',
                    'data' => $data ,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1
                ]]
            ],
            "options"=>[
                "title"=> [
                "display"=> true,
                "text"=> "Numero de ofertas registradas por carrera"
                ]
            ]
        ]));
        $image = file_get_contents($chartUrl);
        $base64 = base64_encode($image);
        $imgSrc = 'data:image/png;base64,' . $base64;

        $ofertasCarreras = Ofertacarrera::join('carrera', 'ofertacarrera.idcarrera', '=', 'carrera.idCarrera')
        ->join('oferta_trabajo', 'ofertacarrera.idoferta', '=', 'oferta_trabajo.idOfertaTrabajo')
        ->select(DB::raw('count(ofertacarrera.idOfertaCarrera) as total_ofertas'), 'oferta_trabajo.SectorEmpre', 'carrera.NombreCarrera')
        ->where('carrera.id_DirCarrera', '=', Auth()->user()->dirCarrera->idDirCarrera)
        ->groupBy('oferta_trabajo.SectorEmpre', 'carrera.NombreCarrera')
        ->get();
        $carreras2 = $ofertasCarreras->pluck('NombreCarrera')->unique()->values()->all();
        $sectores = $ofertasCarreras->pluck('SectorEmpre')->unique()->values()->all();

        // Inicializamos los datasets para cada sector
        $datasets = [];

        foreach ($sectores as $sector) {
            $r = rand(150, 255);
            $g = rand(150, 255);
            $b = rand(150, 255);
            $colors = "rgb($r, $g, $b)";
            // Para cada sector, obtener los datos para cada carrera
            $dataForSector = [];
            
            foreach ($carreras2 as $carrera) {
                // Filtrar por carrera y sector y obtener el total de ofertas
                $totalOfertas = $ofertasCarreras->where('SectorEmpre', $sector)
                                                ->where('NombreCarrera', $carrera)
                                                ->first()->total_ofertas ?? 0;
                $dataForSector[] = $totalOfertas; // Añadir el valor al dataset de ese sector
            }
            
            // Añadir el dataset para este sector
            $datasets[] = [
                'label' => $sector,
                'data' => $dataForSector,
                'backgroundColor' => $colors,
                'borderColor' => $colors,
                'borderWidth' => 1
            ];
        }
        $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode([
            'type' => 'bar',
            'data' => [
                'labels' => $carreras2, 
                'datasets' => $datasets 
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Número de Ofertas por Carrera y Sector'
                ]
            ]
        ]));
        $image = file_get_contents($chartUrl);
        $base64 = base64_encode($image);
        $imgSrc2 = 'data:image/png;base64,' . $base64;

        $CvEnviadosCarreras = CvOfertas::join('egresado', 'cv_ofertas.id_egresado', '=', 'egresado.idEgresado')
        ->join('carrera', 'egresado.Carrera', '=', 'carrera.idCarrera')
        ->join('oferta_trabajo', 'cv_ofertas.id_oferta', '=', 'oferta_trabajo.idOfertaTrabajo')
        ->select(DB::raw('count(cv_ofertas.idCVOferta) as total_cv'), 'carrera.NombreCarrera')
        ->where('carrera.id_DirCarrera', '=', Auth()->user()->dirCarrera->idDirCarrera)
        ->groupBy('carrera.NombreCarrera')
        ->get();
        $carreras2 = $CvEnviadosCarreras->pluck('NombreCarrera');
        $data = $CvEnviadosCarreras->pluck('total_cv');
        $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode([
            'type' => 'bar',
            'data' => [
                'labels' => $carreras2, 
                'datasets' => [[
                    'label' => 'Numero de Cv enviados',
                    'data' => $data ,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1
                ]]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Número de Cv enviados por Carrera '
                ]
            ]
        ]));
        $image = file_get_contents($chartUrl);
        $base64 = base64_encode($image);
        $imgSrc4 = 'data:image/png;base64,' . $base64;

        $pdf = Pdf::loadView('ReportesPDF.reporte2', compact('carreras','datosDirector','imgSrc','imgSrc2','imgSrc4'));
        return $pdf->stream('reporte.pdf');
    }

}
