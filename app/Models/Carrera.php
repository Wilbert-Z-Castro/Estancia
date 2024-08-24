<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carrera';
    protected $primaryKey = 'idCarrera';

    protected $fillable = [
        'NombreCarrera',
        'Descripcion',
        'id_DirCarrera',
        'PlanEstudios',
        'UbicacionOficinas',
    ];

    public function dirCarrera()
    {
        return $this->belongsTo(DirCarrera::class, 'id_DirCarrera');
    }

    public function egresados()
    {
        return $this->hasMany(Egresado::class, 'Carrera');
    }

    public function ofertasTrabajo()
    {
        return $this->belongsToMany(OfertaTrabajo::class, 'ofertacarrera', 'idcarrera', 'idoferta');
    }

}
