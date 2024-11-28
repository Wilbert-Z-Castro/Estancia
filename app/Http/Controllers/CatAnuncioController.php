<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CatAnuncio;

class CatAnuncioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if($request->has('search')){
            if($request->has('search') && $request->search != ''){
                $categorias = CatAnuncio::where('Nombre', 'like', '%' . $request->search . '%')
                ->orwhere('Descripcion', 'like', '%' . $request->search . '%')
                ->orwhere('Color', 'like', '%' . $request->search . '%')
                ->paginate(4)
                ->withQueryString();
                return Inertia::render('Pages_CatAnuncios/index',[
                    'categorias' => $categorias,
                ]);
            }else{
                $categorias = CatAnuncio::paginate(4);
            }
        }else{
            $categorias = CatAnuncio::paginate(4);
        }        
        return Inertia::render('Pages_CatAnuncios/index',[
            'categorias' => $categorias,
        ]);

    }
    public function Buscar(Request $request)
    {
        // Puedes agregar validaciones si es necesario
        $categorias = CatAnuncio::where('Nombre', 'like', '%' . $request->Nombre . '%')->get();

        return response()->json([
            'categorias' => $categorias,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        
        return Inertia::render('Pages_CatAnuncios/form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Descripcion' => 'required|string|max:255',
        ], [
            'Nombre.required' => 'El nombre es obligatorio.',
            'Nombre.string' => 'El nombre debe ser una cadena de texto.',
            'Nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'Descripcion.required' => 'La descripción es obligatoria.',
            'Descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'Descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
        ]);
        
        $categorias= new CatAnuncio();
        $categorias->Nombre = $request->Nombre;
        $categorias->Descripcion = $request->Descripcion;
        if($request->Color != null){
            $categorias->Color = $request->Color;
        }
        $categorias->save();
        return redirect()->route('cat_anuncios.index')->with('message', 'La categoría  '.$request->Nombre.' fue creada exitosamente!');

    }

    /**
     * Display the specified resource.
     */


    /**
     * muestra el formulario para editar el recurso especificado.
     * entrada: $idCatAnuncio (id de la categoría)
     */
    public function edit(string $idCatAnuncio)
    {
        //
        $categoria = CatAnuncio::find($idCatAnuncio);
        return Inertia::render('Pages_CatAnuncios/Editar',[
            'categoria' => $categoria,
        ]);
    }

    /**
     * actualizar el recurso especificado en el almacenamiento.
     * entrada: $request, $id (valores de la solicitud y el id de la categoría)
     * salida: redirige a la vista de categorías con un mensaje de éxito
     * 
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Descripcion' => 'required|string|max:255',
        ], [
            'Nombre.required' => 'El nombre es obligatorio.',
            'Nombre.string' => 'El nombre debe ser una cadena de texto.',
            'Nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'Descripcion.required' => 'La descripción es obligatoria.',
            'Descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'Descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
        ]);
        
        $categorias= CatAnuncio::find($id);
        $categorias->Nombre = $request->Nombre;
        $categorias->Descripcion = $request->Descripcion;
        if($request->Color != null){
            $categorias->Color = $request->Color;
        }
        $categorias->save();
        
        return redirect()->route('cat_anuncios.index')->with('message', 'La categoría  '.$request->Nombre.' fue actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
     
        //
        $categoria = CatAnuncio::find($id);
        $categoria->delete();
        
        return redirect()->route('cat_anuncios.index')->with('message', 'la categoria se elimino exitosamente');
    }
}
