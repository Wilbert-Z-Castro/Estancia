<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Redirect;
use App\Models\ProyectosColab;
use App\Models\PostulacionProyecto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\answerCv;



class ProyectosColabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
     
        //
        $proyectos = ProyectosColab::where('id_Egresado','=',auth()->user()->egresado->idEgresado)->paginate(5);
        return Inertia::render('Pages_ProyectosColab/index', [
            'proyectos' => $proyectos,
        ]);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Pages_ProyectosColab/form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'TituloProyecto' => 'required|string|max:255',
            'Descripcion' => 'required|string',
            'FechaPublicacion' => 'required|date',
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $proyecto = new ProyectosColab();
        $proyecto->TituloProyecto = $request->TituloProyecto;
        $proyecto->Descripcion = $request->Descripcion;
        $proyecto->FechaPublicacion = $request->FechaPublicacion;
        $proyecto->id_Egresado = auth()->user()->egresado->idEgresado;

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                // Generar un nombre único para el archivo
                $timestamp = now()->format('YmdHis');
                $filename = $timestamp . '_' . $file->getClientOriginalName();

                // Almacenar el archivo en el disco 'public' con el nuevo nombre
                $path = $file->storeAs('ProyectosEgresados', $filename, 'public');
                $proyecto->Imagen = $path;

            }
        }
        $proyecto->Tipo = $request->Tipo;
        $proyecto->Carrera = auth()->user()->egresado->Carrera;
        $proyecto->save();
        return redirect()->route('ProyectosColab.index')->with('message', 'Proyecto Publicado exitosamente');


    }

    /**
     * Display the specified resource.
     */


    public function PanelProyectos()
    {
        $proyectos = ProyectosColab::with(['egresado'])
        ->orderBy('FechaPublicacion', 'asc')
        ->paginate(5);
        return Inertia::render('Pages_ProyectosColab/PanelProyectos', [
            'proyectos' => $proyectos,
        ]);
    }

    public function EnviarSolicitud(string $id){
        $proyecto=ProyectosColab::find($id);
        return Inertia::render('Pages_ProyectosColab/EnviarCV', [
            'proyecto' => $proyecto,
        ]);
    }

    public function EnviarCvProyecto(Request $request){
        $request->validate([
            'Mensaje' => 'required|string',
             'imagenes' => 'required|file|mimes:pdf|max:2048'
        ], [
            'Mensaje.required' => 'El mensaje es obligatorio.',
            'Mensaje.string' => 'El mensaje debe ser un texto válido.',
            'imagenes.required' => 'Es obligatorio subir un archivo.',
            'imagenes.file' => 'El archivo debe ser un archivo válido.',
            'imagenes.mimes' => 'El archivo debe ser un PDF.', // Mensaje personalizado para la validación de tipo de archivo
            'imagenes.max' => 'El archivo no debe ser mayor a 2MB.',
        ]);
        


        $PostulacionProyecto = new PostulacionProyecto();
        $PostulacionProyecto->Id_proyecto = $request->Id_proyecto;
        $PostulacionProyecto->Mensaje = $request->Mensaje;
        $PostulacionProyecto->Id_user = auth()->user()->id;
        $PostulacionProyecto->Estado = 'Enviado';
        
        $file = $request->file('imagenes');
        $timestamp = Carbon::now()->format('YmdHis');
        $filename = $timestamp . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('CVsProyectos', $filename, 'public');
        $PostulacionProyecto->Cv = $path;

        $PostulacionProyecto->save();
        return redirect()->route('ProyectosColab.PanelProyectos')->with('message', 'Solicitud enviada exitosamente');

    }


    public function VerSolicitudes(){
        $solicitudes = ProyectosColab::with(['solicitudes:id,name,ApellidoP,ApellidoM,email,Telefono'])
        ->where('id_Egresado','=',auth()->user()->egresado->idEgresado)
        ->get();

        
        return Inertia::render('Pages_ProyectosColab/verCv', [
            'solicitudes' => $solicitudes,
        ]);
    }

    public function show(string $id)
    {
        //
        $postulacion = PostulacionProyecto::findOrFail($id);
        $filePath = $postulacion->Cv;

        if (Storage::disk('public')->exists($filePath)) {
            $file = Storage::disk('public')->get($filePath);
            $mimeType = Storage::disk('public')->mimeType($filePath);

            return response($file, 200)->header('Content-Type', $mimeType);
        }

        abort(404, 'El archivo no existe.');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $Proyecto = ProyectosColab::findOrFail($id);
        return Inertia::render('Pages_ProyectosColab/Editar', [
            'Proyecto' => $Proyecto,
        ]);
    }

    public function destroyImagenProyecto(string $id){
        $proyecto = ProyectosColab::findOrFail($id);
        Storage::disk('public')->delete($proyecto->Imagen);
        $proyecto->Imagen = null;
        $proyecto->save();

        return redirect()->back()->with('message', 'La imagen fue eliminada exitosamente');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'TituloProyecto' => 'required|string|max:255',
            'Descripcion' => 'required|string',
            'FechaPublicacion' => 'required|date',
            'imagenes' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'TituloProyecto.required' => 'El título del proyecto es obligatorio.',
            'TituloProyecto.string' => 'El título del proyecto debe ser una cadena de texto válida.',
            'TituloProyecto.max' => 'El título del proyecto no debe exceder los 255 caracteres.',
            
            'Descripcion.required' => 'La descripción es obligatoria.',
            'Descripcion.string' => 'La descripción debe ser una cadena de texto válida.',
            
            'FechaPublicacion.required' => 'La fecha de publicación es obligatoria.',
            'FechaPublicacion.date' => 'La fecha de publicación debe ser una fecha válida.',
            
            'imagenes.file' => 'El archivo de imagen debe ser un archivo válido.',
            'imagenes.mimes' => 'La imagen debe estar en formato jpeg, png, jpg o gif.',
            'imagenes.max' => 'La imagen no debe exceder los 2048 KB.',
        ]);
        $proyecto = ProyectosColab::findOrFail($id);
        $proyecto->TituloProyecto = $request->TituloProyecto;
        $proyecto->Descripcion = $request->Descripcion;
        $proyecto->FechaPublicacion = $request->FechaPublicacion;
        $proyecto->Tipo = $request->Tipo;
        $proyecto->Carrera = auth()->user()->egresado->Carrera;
        if($request->imagenes!=null ){
            if($request->Imagen!=null){
                if (Storage::disk('public')->exists($proyecto->Imagen)) {
                    Storage::disk('public')->delete($proyecto->Imagen);
                }
            }
            $file = $request->file('imagenes');
            $timestamp = Carbon::now()->format('YmdHis');
            $filename = $timestamp . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('ProyectosEgresados', $filename, 'public');
            $proyecto->Imagen = $path;

        }
        $proyecto->save();
        return redirect()->route('ProyectosColab.index')->with('message', 'Proyecto actualizado exitosamente');
        

    }
    
    public function ReponderSolicitud(Request $request){
        $request->validate([
            'MensajeEgresado' => 'required|string',
        ], [
            'MensajeEgresado.required' => 'El mensaje al estudiante es obligatorio.',
            'MensajeEgresado.string' => 'El mensaje debe ser una cadena de texto válida.',
        ]);
        
        $postulacion = PostulacionProyecto::findOrFail($request->idPostulacionProyecto);
        $postulacion->MensajeEgresado = $request->MensajeEgresado;
        $postulacion->Estado = $request->Estado;
        $postulacion->save();
        $user = $postulacion->usuario->email;
        $mensajeCorreo= PostulacionProyecto::with(['proyecto','proyecto.egresado:idEgresado,Id_user','proyecto.egresado.user:id,name,ApellidoP,ApellidoM,email,Telefono'])
        ->findOrFail($request->idPostulacionProyecto);
        Mail::to($user)->send(new answerCv($mensajeCorreo));
        return redirect()->route('ProyectosColab.VerSolicitudes')->with('message', 'Respuesta enviada exitosamente');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
