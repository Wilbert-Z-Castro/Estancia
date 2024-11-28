<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Redirect;
use App\Models\Ponencias;
use App\Models\Egresado;
use App\Models\Carrera;
use App\Models\AceptacionPonencia;
use App\Models\imagenAceptacion;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;  
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Rules\PonenciaArrayValidation;
use App\Rules\ImagenArrayValidation;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class PonenciasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();
        $id = $user->dirCarrera->idDirCarrera;
        $ponencias = Ponencias::
        select('idPonencias','TituloPonencia','DescripcionPonencia','Fecha','Lugar','imagen','Id_DirCarrera')
        ->where('Id_DirCarrera', '=', $id)
        ->orderBy('created_at', 'desc')
        ->with(['dirCarrera:idDirCarrera','egresados:Id_user','egresados.user:id,name'])  
        ->paginate(12);

        return Inertia::render('Pages_DirCarrera/Ponencias', [
            'ponencias' => $ponencias,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $user = Auth::user();

        // Asegúrate de que dirCarrera y idDirCarrera estén disponibles y sean correctos
        $idDirCarrera = $user->dirCarrera->idDirCarrera;

        // Consulta para obtener las carreras y sus egresados
        $ponentes = Carrera::select('idCarrera', 'NombreCarrera', 'id_DirCarrera')
            ->with([
                'egresados:idEgresado,Carrera,Id_user',
                'egresados.user:id,name'
            ])
            ->where('id_DirCarrera', $idDirCarrera)
            ->get();

        $egresados=Egresado::select('idEgresado','Matricula','Id_user')
        ->with('user:id,name')
        ->get();

        $EgresadosPorCarrera = Egresado::
        selectRaw('idEgresado, Matricula, Id_user, Carrera, name, CONCAT(name," De ",NombreCarrera) as NombreCompleto')
            ->join('carrera', 'egresado.Carrera', '=', 'carrera.idCarrera')
            ->join('users', 'egresado.Id_user', '=', 'users.id')
            ->orderBy('Carrera')
            ->where('carrera.id_DirCarrera', $idDirCarrera)
            ->get();

        return Inertia::render('Pages_Ponencias/form', [
            'ponentes' => $ponentes,
            'egresados' => $egresados,
            'EgresadosPorCarrera' => $EgresadosPorCarrera,
        ]);      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'TituloPonencia' => 'required|string|max:255',
            'DescripcionPonencia' => 'required|string|max:1000',
            'Fecha' => 'required|date',
            'Lugar' => 'required|string|max:255',
            'imagenes' => ['nullable', 'array', new ImagenArrayValidation],
            'egresados' => 'required|array',
        ], [
            'TituloPonencia.required' => 'El campo título de la ponencia es obligatorio.',
            'TituloPonencia.string' => 'El campo título de la ponencia debe ser una cadena de texto.',
            'TituloPonencia.max' => 'El campo título de la ponencia no debe tener más de 255 caracteres.',
            
            'DescripcionPonencia.required' => 'El campo descripción de la ponencia es obligatorio.',
            'DescripcionPonencia.string' => 'El campo descripción de la ponencia debe ser una cadena de texto.',
            'DescripcionPonencia.max' => 'El campo descripción de la ponencia no debe tener más de 1000 caracteres.',
            
            'Fecha.required' => 'El campo fecha es obligatorio.',
            'Fecha.date' => 'El campo fecha debe ser una fecha válida.',
        
            'Lugar.required' => 'El campo lugar es obligatorio.',
            'Lugar.string' => 'El campo lugar debe ser una cadena de texto.',
            'Lugar.max' => 'El campo lugar no debe tener más de 255 caracteres.',
            'egresados.required' => 'El campo egresados es obligatorio.',
            'egresados.array' => 'El campo egresados debe ser un array.',
            'egresados.*.integer' => 'Cada elemento en el campo egresados debe ser un número entero.',
            'egresados.*.exists' => 'El egresado seleccionado no existe.',
        ]);
        $ponencia = new Ponencias();
        $ponencia->TituloPonencia = $request->TituloPonencia;
        $ponencia->DescripcionPonencia = $request->DescripcionPonencia;
        $ponencia->Fecha = $request->Fecha;
        $ponencia->Lugar = $request->Lugar;
        $ponencia->Id_DirCarrera = Auth::user()->dirCarrera->idDirCarrera;

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                // Generar un nombre único para el archivo
                $timestamp = now()->format('YmdHis');
                $filename = $timestamp . '_' . $file->getClientOriginalName();

                // Almacenar el archivo en el disco 'public' con el nuevo nombre
                $path = $file->storeAs('InvitancionPonencia', $filename, 'public');
                $ponencia->imagen = $path;

            }
        }

        $ponencia->save();
        $ponencia->egresados()->attach($request->egresados,['Estado'=>'Pendiente']);

        
        return redirect()->route('Ponencias.index')->with('message', 'La invitacion a la ponencia: '.$request->TituloPonencia.' fue enviada!');



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        

    }

    public function AceptarPonencia(string $id)
    {
        $aceptacionPonencia=AceptacionPonencia::with('ponencia')
        ->find($id);
        return Inertia::render('Pages_Ponencias/AceptarPonencia', [
            'aceptacionPonencia' => $aceptacionPonencia,
        ]);
    }

    public function PonenciaUsuario()
    {

        $idEgresado = Auth::user()->egresado->idEgresado;
        $ponencias = AceptacionPonencia::where('id_Egresado', $idEgresado)
            ->select('idAceptacionPonencia','Estado','Id_Ponencia')
            ->with(['ponencia','ponencia.dirCarrera:idDirCarrera,id_userDir','ponencia.dirCarrera.user:id,name'])
            ->orderBy('created_at', 'desc') 
            ->get();
        return Inertia::render('Pages_Ponencias/PanelPonencias', [
            'ponencias' => $ponencias,
        ]);

    }

    public function Confirmacion(Request $request){
        $request->validate([
            'Mensaje' => 'required|string',
            'imagenes' => ['nullable', 'array', new PonenciaArrayValidation],
        ],[
            'Mensaje.required' => 'El campo mensaje es obligatorio.',
            'Mensaje.string' => 'El campo mensaje debe ser una cadena de texto.',
        ]);
        
        
        $aceptacionPonencia = AceptacionPonencia::find($request->idAceptacion);
        $aceptacionPonencia->Estado = $request->Estado;
        $aceptacionPonencia->Mensaje = $request->Mensaje;
        $aceptacionPonencia->save();
        
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                // Generar un nombre único para el archivo
                $timestamp = now()->format('YmdHis');
                $filename = $timestamp . '_' . $file->getClientOriginalName();
                
                // Almacenar el archivo en el disco 'public' con el nuevo nombre
                $path = $file->storeAs('PonenciasAceptadas', $filename, 'public');
                
                // Crear el registro en la base de datos
                $imagenAcepatcion = new imagenAceptacion();
                $imagenAcepatcion->URLArchivo = $path;
                $imagenAcepatcion->id_Aceptacion = $request->idAceptacion; // Asocia la imagen con el anuncio
                $imagenAcepatcion->save();
            }
        }
        
        
        
        return redirect()->route('Ponencias.MisInvitaciones')->with('message', 'Se ha enviado la confirmación de la ponencia');


        
    }


    public function VerMenesaje(string $id){
        $aceptacionPonencia = AceptacionPonencia::with(['imagenes','ponencia'])
        ->find($id);
        return Inertia::render('Pages_Ponencias/VerMensaje', [
            'aceptacionPonencia' => $aceptacionPonencia,
        ]);
        

    }

    public function RechazarPonencia(Request $request){
        $aceptacionPonencia = AceptacionPonencia::find($request->idAceptacionPonencia);
        if($request->has('Mensaje')){
            $aceptacionPonencia->Mensaje = $request->Mensaje;
        }
        $aceptacionPonencia->Estado = 'Rechazado';
        $aceptacionPonencia->save();


    }

    public function Reconocimiento(string $id){
        $aceptacionPonencia = AceptacionPonencia::with(['ponencia','ponencia.dirCarrera:idDirCarrera,id_userDir','ponencia.dirCarrera.user:id,name,ApellidoP,ApellidoM'])
        ->find($id);
        $aceptacionPonencia->Estado = 'Terminado';
        $aceptacionPonencia->save();
        // Crear un nuevo objeto FPDI
        $egresado=Egresado::with(['user:id,name,ApellidoP,ApellidoM','Carrera:idCarrera,NombreCarrera'])
        ->find($aceptacionPonencia->id_Egresado);


        $pdf = new Fpdi();

        $pdf->AddPage('L', [2000, 1414]);

        // Cargar  de plantilla
        $pdf->Image(public_path('img/plantilla.jpg'), 0, 0, 2000, 1414, 'jpg');

        $pdf->SetFont('Arial', 'B', 200);
        $pdf->SetXY(900, 790);
        $nombreCompleto = $egresado->user->name .' '.$egresado->user->ApellidoP.' '.$egresado->user->ApellidoM;
        $pdf->Cell(200, 10, utf8_decode($nombreCompleto), 0, 0, 'C');

        $date = Carbon::parse($aceptacionPonencia->Fecha)->locale('es');
        $formattedDate = $date->translatedFormat('d \d\e F \d\e Y');
        $pdf->SetFont('Arial', 'B', 90);
        $pdf->SetXY(900, 900);
        $pdf->Cell(200, 10, utf8_decode('Jiutepec, Morelos a: '.$formattedDate), 0, 0, 'C');

        $pdf->SetFont('Arial', 'I', 90);
        $pdf->SetXY(920, 520);
        $pdf->Cell(200, 10, utf8_decode('Por su asistencia en la ponencia:'), 0, 0, 'C');
        $pdf->SetFont('Arial', 'I', 100);
        $pdf->SetXY(910, 590);
        $pdf->Cell(200, 10, utf8_decode($aceptacionPonencia->ponencia->TituloPonencia), 0, 0, 'C');
        $pdf->SetFont('Arial', 'I', 100);
        $pdf->SetXY(910, 700);
        $pdf->Cell(200, 10, utf8_decode('Al egresado:'), 0, 0, 'C');

        $pdf->SetFont('', 'I', 100);
        $pdf->SetXY(590, 1010);
        if($aceptacionPonencia->ponencia->dirCarrera==null){
            $pdf->Cell(200, 10, utf8_decode('Sin Director'), 0, 0, 'C');
        }else{
            
            $pdf->Cell(200, 10, utf8_decode($aceptacionPonencia->ponencia->dirCarrera->user->name.' '.$aceptacionPonencia->ponencia->dirCarrera->user->ApellidoP.' '.$aceptacionPonencia->ponencia->dirCarrera->user->ApellidoM), 0, 0, 'C');
        }

        $pdf->SetFont('Arial','',100);
        $pdf->SetXY(1230, 1010);
        $pdf->Cell(200, 10, utf8_decode('Arturo Mazari Espín'), 0, 0, 'C');

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf');
        
    
        //return view('Plantilla.ReconocimientoPonencia', compact('aceptacionPonencia'));
        //return $pdf->stream('reconocimiento.pdf');
    }

    /** 
     * $pdf = Pdf::loadView('Plantilla.ReconocimientoPonencia', compact('aceptacionPonencia'))
    * ->setPaper('a4', 'landscape'); 
     * $path = public_path('img/UPE_vertical.jpg'); // Ruta a tu imagen
        
     *$base64Image = base64_encode(file_get_contents($path));
     *         <img src="data:image/jpeg;base64,{{ $base64Image }}" alt="Descripción de la imagen" class="centered-image">

     * 
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
