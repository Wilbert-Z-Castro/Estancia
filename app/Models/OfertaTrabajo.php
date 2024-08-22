<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfertaTrabajo extends Model
{
    use HasFactory;

    protected $table = 'OfertaTrabajo';

    protected $fillable = [
        'TituloOferta',
        'Descripcion',
        'Requisitos',
        'CarreraDir',
        'Empresa',
        'Ubicacion',
        'FechaOferta',
        'Imagen',
        'SectorEmpre',
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'CarreraDir');
    }

    public function cvOfertas()
    {
        return $this->hasMany(CvOfertas::class, 'Id_oferta');
    }
}
