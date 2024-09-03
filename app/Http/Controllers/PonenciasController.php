<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Redirect;
use App\Models\Ponencias;
use App\Models\Egresado;
use App\Models\Carrera;
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
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
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
            'imagenes.array' => 'El campo imágenes debe ser un array.',
            'imagenes.*.mimes' => 'Cada imagen debe ser un archivo de tipo: jpeg, png, jpg, gif.',
            'imagenes.*.max' => 'Cada imagen no debe tener más de 2048 kilobytes.',
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
