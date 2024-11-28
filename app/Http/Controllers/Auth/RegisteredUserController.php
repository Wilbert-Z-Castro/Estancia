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
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'Debe proporcionar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
        
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.string' => 'El nombre de usuario debe ser una cadena de texto.',
            'username.max' => 'El nombre de usuario no puede tener más de 45 caracteres.',
        
            'apellidoP.required' => 'El apellido paterno es obligatorio.',
            'apellidoP.string' => 'El apellido paterno debe ser una cadena de texto.',
            'apellidoP.max' => 'El apellido paterno no puede tener más de 45 caracteres.',
        
            'apellidoM.required' => 'El apellido materno es obligatorio.',
            'apellidoM.string' => 'El apellido materno debe ser una cadena de texto.',
            'apellidoM.max' => 'El apellido materno no puede tener más de 45 caracteres.',
        
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no puede tener más de 10 dígitos.',
        
            'sexo.required' => 'El sexo es obligatorio.',
            'sexo.string' => 'El sexo debe ser una cadena de texto.',
            'sexo.max' => 'El sexo no puede tener más de 45 caracteres.',
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
                'adicional' => 'required|string',
                'sectorEmpresarial' => 'required|string|max:45',
            ], [
                'carrera_id.required' => 'La carrera es obligatoria.',
                'matricula.required' => 'La matrícula es obligatoria.',
                'matricula.string' => 'La matrícula debe ser una cadena de texto.',
                'matricula.max' => 'La matrícula no puede tener más de 45 caracteres.',
        
                'anioEgreso.required' => 'El año de egreso es obligatorio.',
                'direccion.required' => 'La dirección es obligatoria.',
                'direccion.string' => 'La dirección debe ser una cadena de texto.',
                'direccion.max' => 'La dirección no puede tener más de 255 caracteres.',
        
                'empresaActual.required' => 'El nombre de la empresa actual es obligatorio.',
                'empresaActual.string' => 'El nombre de la empresa debe ser una cadena de texto.',
                'empresaActual.max' => 'El nombre de la empresa no puede tener más de 45 caracteres.',
        
                'puestoTrabajo.required' => 'El puesto de trabajo es obligatorio.',
                'puestoTrabajo.string' => 'El puesto de trabajo debe ser una cadena de texto.',
                'puestoTrabajo.max' => 'El puesto de trabajo no puede tener más de 45 caracteres.',
        
                'aniosLaboral.required' => 'Los años de experiencia laboral son obligatorios.',
                'adicional.required' => 'El campo adicional es obligatorio.',
                'adicional.string' => 'El campo adicional debe ser una cadena de texto.',
        
                'sectorEmpresarial.required' => 'El sector empresarial es obligatorio.',
                'sectorEmpresarial.string' => 'El sector empresarial debe ser una cadena de texto.',
                'sectorEmpresarial.max' => 'El sector empresarial no puede tener más de 45 caracteres.',
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

        return redirect(RouteServiceProvider::HOME);
    }
}
