<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoColab extends Model
{
    use HasFactory;

    protected $table = 'ProyectoColab';

    protected $fillable = [
        'id_Egresado',
        'TituloProyecto',
        'Descripcion',
        'FechaPublicacion',
        'Imagen',
    ];

    public function egresado()
    {
        return $this->belongsTo(Egresado::class, 'id_Egresado');
    }

    public function postulaciones()
    {
        return $this->hasMany(PostulacionProyecto::class, 'Id_proyecto');
    }
}
