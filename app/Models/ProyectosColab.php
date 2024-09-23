<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectosColab extends Model
{
    use HasFactory;

    protected $table = 'proyecto_colab';
    protected $primaryKey = 'idProyectoColab';

    protected $fillable = [
        'id_Egresado',
        'TituloProyecto',
        'Descripcion',
        'FechaPublicacion',
        'Tipo',
        'Imagen',
    ];

    public function egresado()
    {
        return $this->belongsTo(Egresado::class, 'id_Egresado');
    }

    

    public function solicitudes()
    {
        return $this->belongsToMany(User::class, 'postulacion_proyecto','Id_proyecto','id_user')
            ->withPivot('idPostulacionProyecto','Mensaje','Cv','Estado');
    }

}
