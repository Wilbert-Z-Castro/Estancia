<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class aceptacion_ponencia extends Model
{
    use HasFactory;
    protected $table = 'aceptacion_ponencia';
    protected $primaryKey = 'idAceptacionPonencia';

    protected $fillable = [
        'Id_Ponencia',
        'id_Egresado',
        'Lugar',
        'Estado',
        'Mensaje',
    ];

    public function ponencia()
    {
        return $this->belongsTo(Ponencias::class, 'Id_Ponencia','Id_Ponencia');
        
    }

    
}
