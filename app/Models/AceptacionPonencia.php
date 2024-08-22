<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AceptacionPonencia extends Model
{
    use HasFactory;

    protected $table = 'AceptacionPonencia';

    protected $fillable = [
        'Estado',
        'Id_Ponencia',
        'id_Egresado',
        'Mensaje',
        'Archivo',
    ];

    public function ponencia()
    {
        return $this->belongsTo(Ponencias::class, 'Id_Ponencia');
    }

    public function egresado()
    {
        return $this->belongsTo(Egresado::class, 'id_Egresado');
    }
}
