<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenAnuncio extends Model
{
    use HasFactory;

    protected $table = 'imagen';
    protected $primaryKey = 'IdImagen';

    protected $fillable = [
        'URL',
        'id_relacion',
    ];

    public function anuncio()
{
    return $this->belongsTo(Anuncio::class, 'idAnuncio');
}
}