<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponencias extends Model
{
    use HasFactory;

    protected $table = 'ponencias';
    protected $primaryKey = 'idPonencias';

    protected $fillable = [
        'TituloPonencia',
        'DescripcionPonencia',
        'Fecha',
        'Lugar',
        'Id_DirCarrera',
    ];

    public function dirCarrera()
    {
        return $this->belongsTo(DirCarrera::class, 'Id_DirCarrera');
    }

    public function aceptaciones()
    {
        return $this->belongsToMany(AceptacionPonencia::class, 'aceptacion_ponencia','Id_Ponencia','id_Egresado')
        ->withPivot('Estado');
    }

    public function egresados()
    {
        return $this->belongsToMany(Egresado::class, 'aceptacion_ponencia','Id_Ponencia','id_Egresado')
                    ->withPivot('idAceptacionPonencia','Estado')  
                    ->withTimestamps();  
    }
}
