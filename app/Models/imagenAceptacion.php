<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class imagenAceptacion extends Model
{
    use HasFactory;

    protected $table = 'imagenponencia';
    protected $primaryKey = 'idimagenPonencia';

    protected $fillable = [
        'URLArchivo',
        'id_Aceptacion',
    ];

    public function anuncio()
{
    return $this->belongsTo(AceptacionPonencia::class, 'idAceptacionPonencia');
}
}