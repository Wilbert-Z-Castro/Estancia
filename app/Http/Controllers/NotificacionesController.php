<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Egresado;
use App\Models\aceptacion_ponencia;
use App\Models\AceptacionPonencia;
use Illuminate\Http\JsonResponse;


use Illuminate\Support\Facades\Auth;

class NotificacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
    
        if(Auth::user()->Rol=="Egresado"){
            $id = Auth::user()->id; 
            $idEgresado = Egresado::select('idEgresado')
                ->where('Id_user', $id)->first();
        
            if (!$idEgresado) {
                return response()->json(['error' => 'Egresado no encontrado'], 404);
            }
        
            $notificaciones = AceptacionPonencia::where('id_egresado', $idEgresado->idEgresado)
                ->select('Estado','Id_Ponencia')
                ->where('Estado', 'Pendiente')
                ->with('ponencia:idPonencias,TituloPonencia')
                ->get();
                
            return response()->json($notificaciones);
            /*$user = user::all();
            return response()->json($user);*/
        }
            
    }

    public function NotificionesPonencia()
    {
        return Inertia::render('Pages_DirCarrera/NotificacionesPonencia');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
