<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImagenAnuncio;
use Illuminate\Support\Facades\Storage;

class ImagenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $imagen = ImagenAnuncio::findOrFail($id);

        // Eliminar el archivo de imagen del sistema de archivos
        if (Storage::disk('public')->exists($imagen->URL)) {
            Storage::disk('public')->delete($imagen->URL);
        }

        // Eliminar el registro de la base de datos
        $imagen->delete();
        return redirect()->back()->with('message', 'Imagen eliminada con éxito');

    }
}
