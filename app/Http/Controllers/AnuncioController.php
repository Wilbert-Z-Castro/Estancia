<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Anuncio;
use App\Models\CatAnuncio;
use App\Models\ImagenAnuncio;
use Illuminate\Support\Facades\Auth;


class AnuncioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if($request->has('Titulo') && $request->Titulo != ''){
            $anuncios = Anuncio::with('categoria', 'imagenes')
            ->where('Id_userCreado', Auth()->user()->id)
            ->where('Titulo', 'like', '%'.$request->Titulo.'%')
            ->orWhere('Categoria', 'like', '%'.$request->Titulo.'%')
            ->paginate(10)
            ->withQueryString();
        }else{
            $anuncios = Anuncio::with('categoria', 'imagenes')
            ->where('Id_userCreado', Auth()->user()->id)
            ->paginate(10);
        }

        return Inertia::render('Pages_Anuncios/index', [
            'anuncios' => $anuncios,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categorias = CatAnuncio::all();
        return Inertia::render('Pages_Anuncios/form',[
            'categorias' => $categorias,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del request
        $request->validate([
            'Titulo' => 'required|string|max:255',
            'Categoria' => 'required|integer',
            'Contenido' => 'required|string',
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'Titulo.required' => 'El título es obligatorio.',
            'Titulo.string' => 'El título debe ser una cadena de caracteres.',
            'Titulo.max' => 'El título no puede tener más de 255 caracteres.',
            'Categoria.required' => 'La categoría es obligatoria.',
            'Categoria.integer' => 'La categoría debe ser un número entero.',
            'Contenido.required' => 'El contenido es obligatorio.',
            'Contenido.string' => 'El contenido debe ser una cadena de caracteres.',
            'imagenes.array' => 'Las imágenes deben ser un array.',
            'imagenes.file' => 'Cada archivo debe ser una imagen válida.',
            'imagenes.mimes' => 'Cada imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'imagenes.max' => 'Cada imagen no puede exceder los 2MB.',
        ]);

        // Crear el anuncio
        $anuncio = new Anuncio();
        $anuncio->Titulo = $request->input('Titulo');
        $anuncio->Categoria = $request->input('Categoria');
        $anuncio->Contenido = $request->input('Contenido');
        $anuncio->Id_userCreado = auth()->user()->id;
        $anuncio->save();

        // Manejar las imágenes
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                // Generar un nombre único para el archivo
                $timestamp = now()->format('YmdHis');
                $filename = $timestamp . '_' . $file->getClientOriginalName();

                // Almacenar el archivo en el disco 'public' con el nuevo nombre
                $path = $file->storeAs('anuncios', $filename, 'public');

                // Crear el registro en la base de datos
                $imagenAnuncio = new ImagenAnuncio();
                $imagenAnuncio->URL = $path;
                $imagenAnuncio->id_relacion = $anuncio->idAnuncio; // Asocia la imagen con el anuncio
                $imagenAnuncio->save();
            }
        }

        return redirect()->route('anuncios.index')->with('message', 'Anuncio creado con éxito');
    }


    /**
     * Display the specified resource.
     */
    public function Buscar(Request $request)
    {
        $anuncios = Anuncio::with('categoria', 'imagenes')
        ->where('Id_userCreado', Auth()->user()->id)
        ->where('Titulo', 'like', '%'.$request->Titulo.'%')
        ->get();
        
        return response()->json(
            [
            'anuncios'=>$anuncios,
        ]); 
        
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $anuncio = Anuncio::with('imagenes')->find($id);
        $categorias = CatAnuncio::all();
        return Inertia::render('Pages_Anuncios/Editar', [
            'anuncio' => $anuncio,
            'categorias' => $categorias,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        //dd($request->all());
        
        $request->validate([
            'Titulo' => 'required|string|max:255',
            'Categoria' => 'required|integer',
            'Contenido' => 'required|string',
            'imagenes.*' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imágenes
        ]);
        $anuncio = Anuncio::find($id);
        $anuncio->Titulo = $request->input('Titulo');
        $anuncio->Categoria = $request->input('Categoria');
        $anuncio->Contenido = $request->input('Contenido');
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                    // Generar un nombre único para el archivo
                $timestamp = now()->format('YmdHis');
                $filename = $timestamp . '_' . $file->getClientOriginalName();
    
                    // Almacenar el archivo en el disco 'public' con el nuevo nombre
                $path = $file->storeAs('anuncios', $filename, 'public');
    
                    // Crear el registro en la base de datos
                $imagenAnuncio = new ImagenAnuncio();
                $imagenAnuncio->URL = $path;
                $imagenAnuncio->id_relacion = $anuncio->idAnuncio; // Asocia la imagen con el anuncio
                $imagenAnuncio->save();
            }
        }

        
        $anuncio->save();
        return redirect()->route('anuncios.index')->with('message', 'Anuncio actualizado con éxito');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $anuncio = Anuncio::find($id);
        $anuncio->delete();
        return redirect()->route('anuncios.index')->with('message', 'Anuncio eliminado con éxito');        
    }
}
