<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;
use App\Models\DirCarrera;
use App\Models\Carrera;
use App\Models\Anuncio;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Ponencias;
use App\Models\AceptacionPonencia;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


use Illuminate\Support\Facades\Hash; // Importar la clase Hash

class DirCarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     */


     public function indexPonencia()
     {
         //
         $ponencias = Ponencia::all();
         dd($ponencias);    
 
     }

    
    public function storePonencia(Request $request){

    }

    public function index()
    {
        //
        $DirCarrera = DirCarrera::with('user')->paginate(12); 
        return Inertia::render('Pages_DirCarrera/index', [
            'DirCarrera' => $DirCarrera,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $carreras = Carrera::all();
        return Inertia::render('Pages_DirCarrera/form',[
            'carreras' => $carreras,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:45',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'username' => 'required|string|max:45',
            'apellidoP' => 'required|string|max:45',
            'apellidoM' => 'required|string|max:45',
            'telefono' => 'required|digits:10|regex:/^[0-9]{10}$/',
            'sexo' => 'required|string|max:45',
            'Descripcion' => 'required|string|max:45',
            'FechaAsignacion' => 'required|date',
            'AnioInstitucion' => 'required|integer',
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
            'telefono.max' => 'El campo teléfono no debe tener más de 10 caracteres.',
            'sexo.required' => 'El campo sexo es obligatorio.',
            'Descripcion.required' => 'El campo descripción es obligatorio.',
            'FechaAsignacion.required' => 'El campo fecha de asignación es obligatorio.',
            'AnioInstitucion.required' => 'El campo año de institución es obligatorio.',
            'AnioInstitucion.integer' => 'El campo año de institución debe ser un número entero.',
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
            'rol' => 'DirCarrera',
        ]);

        DirCarrera::create([
            'Descripcion' => $request->input('Descripcion'),
            'FechaAsignacion' => $request->input('FechaAsignacion'),
            'AnioInstitucion' => $request->input('AnioInstitucion'),
            'id_userDir' => $user->id,
        ]);
        event(new Registered($user));

        return redirect()->route('dir_carreras.index')->with('message', 'El Director '.$request->name.' fue creado exitosamente!');
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
        $DirCarrera = DirCarrera::with('user')->find($id);
        return Inertia::render('Pages_DirCarrera/Editar', [
            'DirCarrera' => $DirCarrera,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $DirCarrera = DirCarrera::with('user')->find($id);

    // Obtener el ID del usuario actual
    $userId = $DirCarrera->user->id;


    // Validar solo los campos que se están enviando
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
        'Descripcion' => 'required|string|max:45',
        'FechaAsignacion' => 'required|date',
        'AnioInstitucion' => 'required|integer',
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
        'Descripcion.required' => 'El campo descripción es obligatorio.',
        'FechaAsignacion.required' => 'El campo fecha de asignación es obligatorio.',
        'AnioInstitucion.required' => 'El campo año de institución es obligatorio.',
        'AnioInstitucion.integer' => 'El campo año de institución debe ser un número entero.',
    ];

    // Validar la solicitud
    $validated = $request->validate($rules, $messages);

    // Actualizar los detalles del usuario
    $DirCarrera->user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'username' => $validated['username'],
        'apellidoP' => $validated['apellidoP'],
        'apellidoM' => $validated['apellidoM'],
        'telefono' => $validated['telefono'],
        'sexo' => $validated['sexo'],
    ]);

    if ($request->filled('password')) {
        if (!Hash::check($validated['current_password'], $DirCarrera->user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }
        $DirCarrera->user->update([
            'password' => Hash::make($validated['password']),
        ]);
    }

    $DirCarrera->update([
        'Descripcion' => $validated['Descripcion'],
        'FechaAsignacion' => $validated['FechaAsignacion'],
        'AnioInstitucion' => $validated['AnioInstitucion'],
    ]);

    if($request->MiPerfil==1){
        return redirect()->route('dir_carreras.MiPerfil')->with('message', 'Perfil actualizado exitosamente!');
    }else{
        return redirect()->route('dir_carreras.index')->with('message', 'El Director ' . $validated['name'] . ' fue actualizado exitosamente!');
    }
}

    public function  MiPerfil(){
        $DirCarrera = DirCarrera::with('user')->find(Auth::user()->dirCarrera->idDirCarrera);
        return Inertia::render('Pages_DirCarrera/perfil', [
            'DirCarrera' => $DirCarrera,
        ]);
    }

    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $DirCarrera = DirCarrera::with('user')->find($id);
        $user = User::find($DirCarrera->id_userDir);
        
        $user->delete();
        $DirCarrera->delete();
        return redirect()->route('dir_carreras.index')->with('message', 'Director eliminado con éxito');        
    }


    public function ReportePonencias(){

        

        $datosDirector = DirCarrera::with('user:id,name,email,ApellidoP,ApellidoM,Telefono,Sexo')
        ->where('id_userDir','=',Auth()->user()->id)
        ->first(); 

        $informacionPonencias = Ponencias::with(['egresados:idEgresado,Id_user','egresados.user:id,name,email,ApellidoP,ApellidoM,Telefono,Sexo'])
        ->where('Id_DirCarrera', Auth::user()->dirCarrera->idDirCarrera)
        ->whereYear('created_at', '=', Carbon::now()->year)
        ->orderBy('created_at','desc')
        ->get();

        $informacionAnuncios = Anuncio::with(['categoria:idCatAnuncio,Nombre'])
        ->select('Titulo','Categoria','Contenido','Id_userCreado','created_at')
        ->where('Id_userCreado', Auth::user()->id)
        ->whereYear('created_at', '=', Carbon::now()->year)
        ->orderBy('created_at','desc')
        ->get();

        //egresados con mayor numero de ponencias terminadas
        $egresados = AceptacionPonencia::join('ponencias', 'aceptacion_ponencia.Id_Ponencia', '=', 'ponencias.idPonencias')
        ->join('egresado', 'aceptacion_ponencia.id_Egresado', '=', 'egresado.idEgresado')
        ->join('users', 'egresado.Id_user', '=', 'users.id')
        ->select('egresado.idEgresado','egresado.Id_user','users.name','users.ApellidoP','users.ApellidoM',DB::raw('COUNT(aceptacion_ponencia.idAceptacionPonencia) as total'))
        ->where('aceptacion_ponencia.Estado', 'Terminado')
        ->where('ponencias.Id_DirCarrera', Auth::user()->dirCarrera->idDirCarrera)
        ->whereYear('ponencias.created_at', '=', Carbon::now()->year)
        ->groupBy('egresado.idEgresado','egresado.Id_user','users.name','users.ApellidoP','users.ApellidoM','users.Telefono','users.Sexo')
        ->orderBy('total','desc')
        ->limit(5)
        ->get();

        $numeroDePonencias = $informacionPonencias->count();
        $numeroDeAnuncios = $informacionAnuncios->count();
        
        $datos = [
            'informacionPonencias' => $informacionPonencias,
            'datosDirector' => $datosDirector,
            'informacionAnuncios' => $informacionAnuncios,
            'egresados' => $egresados,
            'numeroDePonencias' => $numeroDePonencias,
            'numeroDeAnuncios' => $numeroDeAnuncios,
        ];
        $pdf = Pdf::loadView('ReportesPDF.reporte3', compact('datos'));
        return $pdf->stream('ReportePonencias.pdf');
    }
}
