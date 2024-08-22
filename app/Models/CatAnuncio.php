<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatAnuncio extends Model
{
    use HasFactory;

    protected $table = 'cat_anuncio';
    protected $primaryKey = 'idCatAnuncio';

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Color',
    ];

    public function anuncios()
    {
        return $this->hasMany(Anuncio::class, 'Categoria', 'idCatAnuncio');
    }
}
