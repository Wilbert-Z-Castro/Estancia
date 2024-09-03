<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Carrera;
use App\Models\Egresado;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {

        $carreras = Carrera::all(); // Obtén todas las carreras disponibles desde el modelo Carrera
        
        return Inertia::render('Auth/Register', [
            'carreras' => $carreras // Pasa las carreras a la vista
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'username' => 'required|string|max:45', 
            'apellidoP' => 'required|string|max:45', 
            'apellidoM' => 'required|string|max:45', 
            'telefono' => 'required|string|max:10', 
            'sexo' => 'required|string|max:45', 
        ]);
        
        if($request->esEgresado == "true"){
            $request->validate([
                'carrera_id' => 'required', 
                'matricula' => 'required|string|max:45', 
                'anioEgreso' => 'required', 
                'direccion' => 'required|string|max:255', 
                'empresaActual' => 'required|string|max:45', 
                'puestoTrabajo' => 'required|string|max:45', 
                'aniosLaboral' => 'required', 
                'adicional' => 'required|string|max:255', 
                'sectorEmpresarial' => 'required|string|max:45', 
            ]);
            $request->rol = "Egresado";

        }else{
            if($request->esEgresado == "representante"){
                $request->rol = "Representante";
            }else{
                $request->rol = "Alumno";
            }
            
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol, 
            'username' => $request->username,
            'apellidoP' => $request->apellidoP, 
            'apellidoM' => $request->apellidoM, 
            'telefono' => $request->telefono, 
            'sexo' => $request->sexo, 
        ]);
        
        if($request->esEgresado == "true"){
            $egresado = Egresado::create([
                'Id_user' => $user->id,
                'Carrera' => $request->carrera_id,
                'Matricula' => $request->matricula,
                'AnioEgreso' => $request->anioEgreso,
                'Direccion' => $request->direccion,
                'EmpresaActual' => $request->empresaActual,
                'PuestoTrabajo' => $request->puestoTrabajo,
                'AniosLaboral' => $request->aniosLaboral,
                'Adicional' => $request->adicional,
                'SectorEmpresaria' => $request->sectorEmpresarial,
            ]);
            

        }

        event(new Registered($user));

        Auth::login($user);

        switch ($user->Rol) {
            case 'Egresado':
                return redirect()->route('anuncios.index');
            case 'DirCarrera':
                return redirect()->route('egresados.index');
            default:
                return redirect()->route('dashboard');
        }
    }
}
