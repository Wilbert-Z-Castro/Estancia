<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Carrera;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Egresado;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EgresadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();
        if($user->Rol == 'DirCarrera'){
            $id = $user->dirCarrera->idDirCarrera;
            
            $egresados = Egresado::join('carrera', 'egresado.Carrera', '=', 'carrera.idCarrera')
            ->where('carrera.id_DirCarrera', $id)
            ->with('user', 'carrera') 
            ->paginate(12);
            return Inertia::render('Pages_Egresados/index', [
                'egresados' => $egresados,
            ]);

        }
        $egresados = Egresado::with('user','Carrera')
        ->paginate(12);
        return Inertia::render('Pages_Egresados/index', [
            'egresados' => $egresados,
        ]);

        /*$users = User::with('egresado')
        ->where('rol', 'Egresado')
        ->paginate(12);
        return Inertia::render('Pages_Egresados/index', [
            'users' => $users,
        ]);*/

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $carreras = Carrera::all();
        return Inertia::render('Pages_Egresados/form',[
            'carreras' => $carreras,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //dd($request);
        $request->validate([
            'name' => 'required|string|max:45',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'username' => 'required|string|max:45',
            'apellidoP' => 'required|string|max:45',
            'apellidoM' => 'required|string|max:45',
            'telefono' => 'required|digits:10|regex:/^[0-9]{10}$/',
            'sexo' => 'required|string|max:45',
            'Carrera' => 'required|integer',
            'Matricula' => 'required|string|max:45',
            'AnioEgreso' => 'required|integer|min:2007',
            'Direccion' => 'required|string|max:45',
            'EmpresaActual' => 'required|string|max:100',
            'PuestoTrabajo' => 'required|string|max:100',
            'AniosLaboral' => 'required|integer|min:1',
            'Adicional' => 'required|string|max:255',
            'SectorEmpresaria' => 'required|string|max:100',
        ], [
            'telefono.regex' => 'El campo teléfono debe ser un número de 10 dígitos.',
            'name.required' => 'El campo nombre es obligatorio.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El campo correo electrónico debe ser una dirección de correo válida.',
            'email.unique' => 'El correo electrónico ya ha sido tomado.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'username.required' => 'El campo nombre de usuario es obligatorio.',
            'apellidoP.required' => 'El campo apellido paterno es obligatorio.',
            'apellidoM.required' => 'El campo apellido materno es obligatorio.',
            'telefono.required' => 'El campo teléfono es obligatorio.',
            'telefono.digits' => 'El campo teléfono debe contener exactamente 10 dígitos.',
            'sexo.required' => 'El campo sexo es obligatorio.',
            'Carrera.required' => 'El campo carrera es obligatorio.',
            'Carrera.integer' => 'El campo carrera debe ser un número entero.',
            'Matricula.required' => 'El campo matrícula es obligatorio.',
            'Matricula.max' => 'El campo matrícula no debe tener más de 45 caracteres.',
            'AnioEgreso.required' => 'El campo año de egreso es obligatorio.',
            'AnioEgreso.integer' => 'El campo año de egreso debe ser un número entero.',
            'AnioEgreso.min' => 'El campo año de egreso debe ser al menos 2007.',
            'Direccion.required' => 'El campo dirección es obligatorio.',
            'Direccion.max' => 'El campo dirección no debe tener más de 45 caracteres.',
            'EmpresaActual.required' => 'El campo empresa actual es obligatorio.',
            'EmpresaActual.max' => 'El campo empresa actual no debe tener más de 100 caracteres.',
            'PuestoTrabajo.required' => 'El campo puesto de trabajo es obligatorio.',
            'PuestoTrabajo.max' => 'El campo puesto de trabajo no debe tener más de 100 caracteres.',
            'AniosLaboral.required' => 'El campo años laborales es obligatorio.',
            'AniosLaboral.integer' => 'El campo años laborales debe ser un número entero.',
            'AniosLaboral.min' => 'El campo años laborales debe ser al menos 1.',
            'Adicional.required' => 'El campo información adicional es obligatorio.',
            'Adicional.max' => 'El campo información adicional no debe tener más de 255 caracteres.',
            'SectorEmpresaria.required' => 'El campo sector empresarial es obligatorio.',
            'SectorEmpresaria.max' => 'El campo sector empresarial no debe tener más de 100 caracteres.',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'username' => $request->input('username'),
            'apellidoP' => $request->input('apellidoP'),
            'apellidoM' => $request->input('apellidoM'),
            'telefono' => $request->input('telefono'),
            'sexo' => $request->input('sexo'),
            'rol' => 'Egresado',
        ]);

        $regresado = Egresado::create([
            'Id_user'=>$user->id,
            'Carrera'=>$request->input('Carrera'),
            'Matricula'=>$request->input('Matricula'),
            'AnioEgreso'=>$request->input('AnioEgreso'),
            'Direccion'=>$request->input('Direccion'),
            'EmpresaActual'=>$request->input('EmpresaActual'),
            'PuestoTrabajo'=>$request->input('PuestoTrabajo'),
            'AniosLaboral'=>$request->input('AniosLaboral'),
            'Adicional'=>$request->input('Adicional'),
            'SectorEmpresaria'=>$request->input('SectorEmpresaria'),
        ]);
        event(new Registered($regresado));
        return redirect()->route('egresados.index')->with('message', 'El Egresado '.$request->name.' fue creado exitosamente!');
        
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
        $Egresado = Egresado::with('user')->find($id);
        $carreras = Carrera::all();
        return Inertia::render('Pages_Egresados/Editar', [
            'Egresado' => $Egresado,
            'carreras' => $carreras,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $Egresado = Egresado::with('user')->find($id);
        $userId = $Egresado->user->id;
        $rules = [
            'name' => 'required|string|max:45',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'username' => 'required|string|max:45',
            'apellidoP' => 'required|string|max:45',
            'apellidoM' => 'required|string|max:45',
            'telefono' => 'required|digits:10|regex:/^[0-9]{10}$/',
            'sexo' => 'required|string|max:45',
            'Carrera' => 'required|integer',
            'Matricula' => 'required|string|max:45',
            'AnioEgreso' => 'required|integer|min:2007',
            'Direccion' => 'required|string|max:45',
            'EmpresaActual' => 'required|string|max:100',
            'PuestoTrabajo' => 'required|string|max:100',
            'AniosLaboral' => 'required|integer|min:1',
            'Adicional' => 'required|string|max:255',
            'SectorEmpresaria' => 'required|string|max:100',

        ];
        $messages = [
            'current_password.required_with' => 'La contraseña actual es necesaria si está cambiando la contraseña.',
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'telefono.regex' => 'El campo teléfono debe ser un número de 10 dígitos.',
            'name.required' => 'El campo nombre es obligatorio.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El campo correo electrónico debe ser una dirección de correo válida.',
            'email.unique' => 'El correo electrónico ya ha sido tomado.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'username.required' => 'El campo nombre de usuario es obligatorio.',
            'apellidoP.required' => 'El campo apellido paterno es obligatorio.',
            'apellidoM.required' => 'El campo apellido materno es obligatorio.',
            'telefono.required' => 'El campo teléfono es obligatorio.',
            'sexo.required' => 'El campo sexo es obligatorio.',
            'Carrera.required' => 'El campo carrera es obligatorio.',
            'Carrera.integer' => 'El campo carrera debe ser un número entero.',
            'Matricula.required' => 'El campo matrícula es obligatorio.',
            'Matricula.max' => 'El campo matrícula no debe tener más de 45 caracteres.',
            'AnioEgreso.required' => 'El campo año de egreso es obligatorio.',
            'AnioEgreso.integer' => 'El campo año de egreso debe ser un número entero.',
            'AnioEgreso.min' => 'El campo año de egreso debe ser al menos 2007.',
            'Direccion.required' => 'El campo dirección es obligatorio.',
            'Direccion.max' => 'El campo dirección no debe tener más de 45 caracteres.',
            'EmpresaActual.required' => 'El campo empresa actual es obligatorio.',
            'EmpresaActual.max' => 'El campo empresa actual no debe tener más de 100 caracteres.',
            'PuestoTrabajo.required' => 'El campo puesto de trabajo es obligatorio.',
            'PuestoTrabajo.max' => 'El campo puesto de trabajo no debe tener más de 100 caracteres.',
            'AniosLaboral.required' => 'El campo años laborales es obligatorio.',
            'AniosLaboral.integer' => 'El campo años laborales debe ser un número entero.',
            'AniosLaboral.min' => 'El campo años laborales debe ser al menos 1.',
            'Adicional.required' => 'El campo información adicional es obligatorio.',
            'Adicional.max' => 'El campo información adicional no debe tener más de 255 caracteres.',
            'SectorEmpresaria.required' => 'El campo sector empresarial es obligatorio.',
            'SectorEmpresaria.max' => 'El campo sector empresarial no debe tener más de 100 caracteres.',
        ];
        $validated = $request->validate($rules, $messages);

        $Egresado->user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'apellidoP' => $request->input('apellidoP'),
            'apellidoM' => $request->input('apellidoM'),
            'telefono' => $request->input('telefono'),
            'sexo' => $request->input('sexo'),
        ]);
        // Si se proporciona una nueva contraseña, actualizarla
        if ($request->filled('password')) {
            // Verificar la contraseña actual
            if (!Hash::check($request['current_password'], $Egresado->user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
            }

            // Actualizar la contraseña
            $Egresado->user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }
        $Egresado->update([
            'Carrera' => $request->input('Carrera'),
            'Matricula' => $request->input('Matricula'),
            'AnioEgreso' => $request->input('AnioEgreso'),
            'Direccion' => $request->input('Direccion'),
            'EmpresaActual' => $request->input('EmpresaActual'),
            'PuestoTrabajo' => $request->input('PuestoTrabajo'),
            'AniosLaboral' => $request->input('AniosLaboral'),
            'Adicional' => $request->input('Adicional'),
            'SectorEmpresaria' => $request->input('SectorEmpresaria'),
        ]);

        return redirect()->route('egresados.index')->with('message', 'Egresado '.$request->name.' actualizado con éxito');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // dd($id);
       
        $egresado = Egresado::with('user')->find($id);
        $user = User::find($egresado->Id_user);
        $user->delete();
        $egresado->delete();    
        return redirect()->route('egresados.index')->with('message', 'Egresado eliminado con éxito');        
    }
}
