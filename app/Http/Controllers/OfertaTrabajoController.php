<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\OfertaTrabajo;
use App\Models\Carrera;
use App\Models\ofertaCarrera;
use Illuminate\Support\Facades\Storage;
class OfertaTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $ofertas = OfertaTrabajo::with(['ofertasCarreras.carrera:idCarrera,NombreCarrera'])->paginate(10);
        return Inertia::render('Pages_OfertaTrabajo/index', [
            'ofertas' => $ofertas,
        ]);
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
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'file|mimes:jpeg,png,jpg,gif|max:2048',

            
        ]);

        $oferta = new OfertaTrabajo();
        $oferta->TituloOferta = $request->TituloOferta;
        $oferta->Descripcion = $request->Descripcion;
        $oferta->Ubicacion = $request->Ubicacion;
        $oferta->Requisitos = $request->Requisitos;
        $oferta->Empresa = $request->Empresa;
        $oferta->SectorEmpre = $request->SectorEmpre;


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
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
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
                $path = $file->storeAs('PlanDeEstudio', $filename, 'public');
                $oferta->Imagen = $path;

            }
        }
        $oferta->save();
        $oferta->carreras()->sync($request->carreras);
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
        return redirect()->route('ofertasTrabajo.index')->with('message', 'La oferta fue eliminada exitosamente!');
    }

    
}
