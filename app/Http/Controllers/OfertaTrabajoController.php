<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\OfertaTrabajo;
use App\Models\Carrera;
use App\Models\ofertaCarrera;
use App\Models\CvOfertas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class OfertaTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if($request->has('search') && $request->search != ''){
            $ofertas = OfertaTrabajo::with(['ofertasCarreras.carrera:idCarrera,NombreCarrera'])
            ->where('TituloOferta','LIKE','%'.$request->search.'%')
            ->orWhere('Descripcion','LIKE','%'.$request->search.'%')
            ->orWhere('Ubicacion','LIKE','%'.$request->search.'%')
            ->orWhere('Requisitos','LIKE','%'.$request->search.'%')
            ->orWhere('Empresa','LIKE','%'.$request->search.'%')
            ->orWhere('SectorEmpre','LIKE','%'.$request->search.'%')
            ->paginate(10)
            ->withQueryString();
            return Inertia::render('Pages_OfertaTrabajo/index', [
                'ofertas' => $ofertas,
            ]);
        }else{
            $ofertas = OfertaTrabajo::with(['ofertasCarreras.carrera:idCarrera,NombreCarrera'])->paginate(10);
        }
        return Inertia::render('Pages_OfertaTrabajo/index', [
            'ofertas' => $ofertas,
        ]);
    }
    public function formularioCV(string $id)
    {
        //
        $oferta = OfertaTrabajo::select('idOfertaTrabajo','TituloOferta')->find($id);
        return Inertia::render('Pages_OfertaTrabajo/EnvioCV', [
            'oferta' => $oferta,
        ]);
    }
    public function EnvioCV(Request $request)
    {   
        

        $user = Auth::user();
        $egresado = $user->egresado;
        //
        
        $request->validate([
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'file|mimes:pdf,jpeg,png,jpg,gif|max:2048',
        ]);
        $cvoferta= new CvOfertas();
        $cvoferta->Id_oferta=$request->Id_oferta;
        if($request->Mensaje!=null){
            $cvoferta->Mensaje=$request->Mensaje;
        }
        $cvoferta->id_egresado=$egresado->idEgresado;
        
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                // Generar un nombre único para el archivo
                $timestamp = now()->format('YmdHis');
                $filename = $timestamp . '_' . $file->getClientOriginalName();

                // Almacenar el archivo en el disco 'public' con el nuevo nombre
                $path = $file->storeAs('CVs', $filename, 'public');
                $cvoferta->Cv = $path;
            }
        }
        $cvoferta->save();
        return redirect()->route('Ofertas.OfertasTrabajo')->with('message', 'El CV fue enviado exitosamente!');

        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $carreras = Carrera::select('idCarrera','NombreCarrera')->get();
        return Inertia::render('Pages_OfertaTrabajo/form', [
            'carreras' => $carreras,
        ]);
    }

    public function CVsOfertas(){
        
        $cvsEnviados = OfertaTrabajo::with('cvOfertas')->where('idUser',Auth::user()->id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        return Inertia::render('Pages_Paneles/CvsRepresentante', [
            'cvsEnviados' => $cvsEnviados,
        ]);
    }

    public function filedownload($filename){
        if (Storage::disk('public')->exists($filename)) {
            // Devuelve el archivo para descarga
            return Storage::disk('public')->download($filename);
        }
    }

    public function GestionOfertas(Request $request){

        
        if($request->has('search') && $request->search != ''){
            $ofertas = OfertaTrabajo::with(['ofertasCarreras.carrera:idCarrera,NombreCarrera'])
            ->where('idUser',Auth::user()->id)
            ->where('TituloOferta','LIKE','%'.$request->search.'%')
            ->orWhere('Descripcion','LIKE','%'.$request->search.'%')
            ->orWhere('Ubicacion','LIKE','%'.$request->search.'%')
            ->orWhere('Requisitos','LIKE','%'.$request->search.'%')
            ->orWhere('Empresa','LIKE','%'.$request->search.'%')
            ->orWhere('SectorEmpre','LIKE','%'.$request->search.'%')
            ->paginate(10)
            ->withQueryString();

        }else{
            $ofertas = OfertaTrabajo::with(['ofertasCarreras.carrera:idCarrera,NombreCarrera'])
            ->where('idUser',Auth::user()->id)->paginate(10);
        }

        return Inertia::render('Pages_OfertaTrabajo/index', [
            'ofertas' => $ofertas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        
        
        $request->validate([
            'TituloOferta' => 'required|string|max:100', 
            'Descripcion' => 'required|string|max:200',
            'Ubicacion' => 'required|string|max:100',
            'Requisitos' => 'required|string|max:200',
            'Empresa'=> 'required|string|max:100',
            'SectorEmpre'=> 'required|string|max:100',
            'carreras'=>'required|array',
            'imagenes' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'TituloOferta.required' => 'El campo título de la oferta es obligatorio.',
            'TituloOferta.string' => 'El título de la oferta debe ser un texto válido.',
            'TituloOferta.max' => 'El título de la oferta no debe exceder los 100 caracteres.',
            
            'Descripcion.required' => 'La descripción es obligatoria.',
            'Descripcion.string' => 'La descripción debe ser un texto válido.',
            'Descripcion.max' => 'La descripción no debe exceder los 200 caracteres.',
            
            'Ubicacion.required' => 'La ubicación es obligatoria.',
            'Ubicacion.string' => 'La ubicación debe ser un texto válido.',
            'Ubicacion.max' => 'La ubicación no debe exceder los 100 caracteres.',
            
            'Requisitos.required' => 'Los requisitos son obligatorios.',
            'Requisitos.string' => 'Los requisitos deben ser un texto válido.',
            'Requisitos.max' => 'Los requisitos no deben exceder los 200 caracteres.',
            
            'Empresa.required' => 'El nombre de la empresa es obligatorio.',
            'Empresa.string' => 'El nombre de la empresa debe ser un texto válido.',
            'Empresa.max' => 'El nombre de la empresa no debe exceder los 100 caracteres.',
            
            'SectorEmpre.required' => 'El sector de la empresa es obligatorio.',
            'SectorEmpre.string' => 'El sector de la empresa debe ser un texto válido.',
            'SectorEmpre.max' => 'El sector de la empresa no debe exceder los 100 caracteres.',
            
            'carreras.required' => 'Debes seleccionar al menos una carrera.',
            
            'imagenes.file' => 'el archivo debe ser una imagen válida: jpeg, png, jpg o gif.',
            'imagenes.mimes' => 'La Imagen deben ser de los tipos: jpeg, png, jpg o gif.',
            'imagenes.max' => 'El tamaño de la imagen no debe exceder los 2048 KB.',
        ]);
        

        $oferta = new OfertaTrabajo();
        $oferta->TituloOferta = $request->TituloOferta;
        $oferta->Descripcion = $request->Descripcion;
        $oferta->Ubicacion = $request->Ubicacion;
        $oferta->Requisitos = $request->Requisitos;
        $oferta->Empresa = $request->Empresa;
        $oferta->SectorEmpre = $request->SectorEmpre;
        $oferta->idUser = Auth::user()->id;


        $oferta->FechaOferta=now();
        $oferta->save();
        foreach ($request->carreras as $idCarrera) {
            $ofertaCarrera = new ofertaCarrera();
            $ofertaCarrera->idoferta = $oferta->idOfertaTrabajo;
            $ofertaCarrera->idcarrera = $idCarrera;
            $ofertaCarrera->save();
        }

        
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                // Generar un nombre único para el archivo
                $timestamp = now()->format('YmdHis');
                $filename = $timestamp . '_' . $file->getClientOriginalName();
                // Almacenar el archivo en el disco 'public' con el nuevo nombre
                $path = $file->storeAs('OfertasTrabajo', $filename, 'public');
                $oferta->Imagen = $path;
            }
        }
        $oferta->save();

        if(Auth::user()->Rol=="Representante"){
            return redirect()->route('CVsOfertas.GestionOfertas')->with('message', 'La oferta '.$request->TituloOferta.' fue creada exitosamente!');

        }
        return redirect()->route('ofertasTrabajo.index')->with('message', 'La oferta '.$request->TituloOferta.' fue creada exitosamente!');
        
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
        $oferta = OfertaTrabajo::with(['ofertasCarreras.carrera:idCarrera,NombreCarrera'])->find($id);
        $carreras = Carrera::select('idCarrera','NombreCarrera')->get();
        return Inertia::render('Pages_OfertaTrabajo/Editar', [
            'oferta' => $oferta,
            'carreras' => $carreras,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'TituloOferta' => 'required|string|max:100', 
            'Descripcion' => 'required|string|max:200',
            'Ubicacion' => 'required|string|max:100',
            'Requisitos' => 'required|string|max:200',
            'Empresa'=> 'required|string|max:100',
            'SectorEmpre'=> 'required|string|max:100',
            'carreras'=>'required|array',
            'imagenes' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'TituloOferta.required' => 'El campo título de la oferta es obligatorio.',
            'TituloOferta.string' => 'El título de la oferta debe ser un texto válido.',
            'TituloOferta.max' => 'El título de la oferta no debe exceder los 100 caracteres.',
            
            'Descripcion.required' => 'La descripción es obligatoria.',
            'Descripcion.string' => 'La descripción debe ser un texto válido.',
            'Descripcion.max' => 'La descripción no debe exceder los 200 caracteres.',
            
            'Ubicacion.required' => 'La ubicación es obligatoria.',
            'Ubicacion.string' => 'La ubicación debe ser un texto válido.',
            'Ubicacion.max' => 'La ubicación no debe exceder los 100 caracteres.',
            
            'Requisitos.required' => 'Los requisitos son obligatorios.',
            'Requisitos.string' => 'Los requisitos deben ser un texto válido.',
            'Requisitos.max' => 'Los requisitos no deben exceder los 200 caracteres.',
            
            'Empresa.required' => 'El nombre de la empresa es obligatorio.',
            'Empresa.string' => 'El nombre de la empresa debe ser un texto válido.',
            'Empresa.max' => 'El nombre de la empresa no debe exceder los 100 caracteres.',
            
            'SectorEmpre.required' => 'El sector de la empresa es obligatorio.',
            'SectorEmpre.string' => 'El sector de la empresa debe ser un texto válido.',
            'SectorEmpre.max' => 'El sector de la empresa no debe exceder los 100 caracteres.',
            
            'carreras.required' => 'Debes seleccionar al menos una carrera.',
            
            'imagenes.file' => 'el archivo debe ser una imagen válida: jpeg, png, jpg o gif.',
            'imagenes.mimes' => 'La Imagen deben ser de los tipos: jpeg, png, jpg o gif.',
            'imagenes.max' => 'El tamaño de la imagen no debe exceder los 2048 KB.',
        ]);
        $oferta = OfertaTrabajo::find($id);
        $oferta->TituloOferta = $request->TituloOferta;
        $oferta->Descripcion = $request->Descripcion;
        $oferta->Ubicacion = $request->Ubicacion;
        $oferta->Requisitos = $request->Requisitos;
        $oferta->Empresa = $request->Empresa;
        $oferta->SectorEmpre = $request->SectorEmpre;
        
        
        
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                // Generar un nombre único para el archivo
                $timestamp = now()->format('YmdHis');
                $filename = $timestamp . '_' . $file->getClientOriginalName();
                if($oferta->Imagen!=null){
                    Storage::disk('public')->delete($oferta->Imagen);
                }
                
                $oferta->Imagen =null;
                // Almacenar el archivo en el disco 'public' con el nuevo nombre
                $path = $file->storeAs('OfertasTrabajo', $filename, 'public');
                $oferta->Imagen = $path;

            }
        }
        $oferta->save();
        $oferta->carreras()->sync($request->carreras);

        if(Auth::user()->Rol=="Representante"){
            return redirect()->route('CVsOfertas.GestionOfertas')->with('message', 'La oferta '.$request->TituloOferta.' fue creada exitosamente!');
        }
        return redirect()->route('ofertasTrabajo.index')->with('message', 'La oferta '.$request->TituloOferta.' fue actualizada exitosamente!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $oferta = OfertaTrabajo::find($id);
        if($oferta->Imagen!=null){
            if (Storage::disk('public')->exists($oferta->Imagen)){
                Storage::disk('public')->delete($oferta->Imagen);
            }
        }
            
        $oferta->delete();
        if(Auth::user()->Rol="Representante"){
            return redirect()->route('CVsOfertas.GestionOfertas')->with('message', 'La oferta fue eliminada exitosamente!');

        }
        return redirect()->route('ofertasTrabajo.index')->with('message', 'La oferta fue eliminada exitosamente!');
    }

    
}
