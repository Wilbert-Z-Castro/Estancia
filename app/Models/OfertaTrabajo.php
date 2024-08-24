<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfertaTrabajo extends Model
{
    use HasFactory;

    protected $table = 'oferta_trabajo';
    protected $primaryKey = 'idOfertaTrabajo';

    protected $fillable = [
        'TituloOferta',
        'Descripcion',
        'Requisitos',
        'Empresa',
        'Ubicacion',
        'FechaOferta',
        'Imagen',
        'SectorEmpre',
    ];

    public function ofertasCarreras()
    {
        return $this->hasMany(ofertaCarrera::class, 'idoferta');
    }

    public function cvOfertas()
    {
        return $this->hasMany(CvOfertas::class, 'Id_oferta');
    }

    public function carreras()
    {
        // Define la relación de muchos a muchos con Carrera
        return $this->belongsToMany(Carrera::class, 'ofertacarrera', 'idoferta', 'idcarrera');
    }

}
