<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostulacionProyecto extends Model
{
    use HasFactory;

    protected $table = 'postulacion_proyecto';
    protected $primaryKey = 'idPostulacionProyecto';

    protected $fillable = [
        'Id_proyecto',
        'Mensaje',
        'Cv',
        'id_user',
        'Estado',
    ];

    

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function proyecto()
    {
        return $this->belongsTo(ProyectosColab::class, 'Id_proyecto');
    }

    
}
