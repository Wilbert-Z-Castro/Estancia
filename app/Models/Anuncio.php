<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anuncio extends Model
{
    use HasFactory;

    protected $table = 'anuncio';
    protected $primaryKey = 'idAnuncio';

    protected $fillable = [
        'Titulo',
        'Categoria',
        'Contenido',
        'Id_userCreado',
    ];

    public function categoria()
    {
        return $this->belongsTo(CatAnuncio::class, 'Categoria', 'idCatAnuncio');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'Id_userCreado');
    }
    
    public function imagenes()
    {
        return $this->hasMany(ImagenAnuncio::class, 'id_relacion', 'idAnuncio');
    }
}