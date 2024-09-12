<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Anuncio;
use App\Models\CatAnuncio;
use App\Models\OfertaTrabajo;
use App\Models\ofertaCarrera;
use App\Models\Egresado;
use App\Models\Carrera;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

    }
    public function Token(){
        $token = Auth::user()->createToken('Token-user')->plainTextToken;
        return response()->json(['token' => $token]);
    }

    public function dashboard()
    {
        //
        switch (Auth::user()->Rol) {
            case 'Egresado':
                return redirect()->route('Panel.Noticias');
            case 'DirCarrera':
                return redirect()->route('egresados.index');
            case 'Representante':
                    return redirect()->route('CVsOfertas.GestionOfertas');
            default:
                return Inertia::render('Dashboard');
        }
        return Inertia::render('Dashboard');
    }

    public function Noticias()
    {
        //
        $anuncios = Anuncio::selectRaw('*, DATE_FORMAT(created_at, "%d/%m/%Y") as formatted_created_at')
        ->with(['categoria', 'imagenes' => function ($query) {
            $query->select('id_relacion', 'URL');
        }])
        ->orderBy('created_at', 'desc') 
        ->get();
        $categorias = CatAnuncio::all();
        return Inertia::render('Pages_Paneles/Noticias',[
            'anuncios' => $anuncios,
            'categorias' => $categorias,
        ]);   
    }
    public function NoticiasCategoriaSelc(string $id)
    {
        //
        
        $anuncios = Anuncio::selectRaw('*, DATE_FORMAT(created_at, "%d/%m/%Y") as formatted_created_at')
        ->with([ 'imagenes' => function ($query) {
            $query->select('id_relacion', 'URL');
        }])
        ->where('Categoria', $id)
        ->orderBy('created_at', 'desc') 
        ->get();
        $categorias = CatAnuncio::all();
        return Inertia::render('Pages_Paneles/Noticias',[
            'anuncios' => $anuncios,
            'categorias' => $categorias,
        ]);  
    }


    public function OfertasTrabajo(){

        $carreraUser = Egresado::where('Id_user', Auth::user()->id)->first()->carrera;        
        $ofertas = OfertaTrabajo::selectRaw('oferta_trabajo.*, DATE_FORMAT(oferta_trabajo.created_at, "%d/%m/%Y") as formatted_created_at')
        ->with('ofertasCarreras.carrera:idCarrera,NombreCarrera')
        ->join('ofertacarrera', 'oferta_trabajo.idOfertaTrabajo', '=', 'ofertacarrera.idoferta')
        ->join('carrera', 'ofertacarrera.idcarrera', '=', 'carrera.idCarrera')
        ->orderBy('oferta_trabajo.created_at', 'desc') 
        ->where('carrera.idCarrera', $carreraUser->idCarrera)
        ->get();
        $carreras = Carrera::select('idCarrera','NombreCarrera')->get();

        return Inertia::render('Pages_Paneles/OferTrabajos',[
            'ofertas' => $ofertas,
            'carreras' => $carreras,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

     public function OfertasTrabajoSelect(string $id){

        $ofertas = OfertaTrabajo::selectRaw('oferta_trabajo.*, DATE_FORMAT(oferta_trabajo.created_at, "%d/%m/%Y") as formatted_created_at')
        ->with('ofertasCarreras.carrera:idCarrera,NombreCarrera')
        ->join('ofertacarrera', 'oferta_trabajo.idOfertaTrabajo', '=', 'ofertacarrera.idoferta')
        ->join('carrera', 'ofertacarrera.idcarrera', '=', 'carrera.idCarrera')
        ->orderBy('oferta_trabajo.created_at', 'desc') 
        ->where('carrera.idCarrera', $id)
        ->get();
        $carreras = Carrera::select('idCarrera','NombreCarrera')->get();

        return Inertia::render('Pages_Paneles/OferTrabajos',[
            'ofertas' => $ofertas,
            'carreras' => $carreras,
        ]);
    }

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
