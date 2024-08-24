<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;
use App\Models\DirCarrera;
use Illuminate\Support\Facades\Storage;
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
}
