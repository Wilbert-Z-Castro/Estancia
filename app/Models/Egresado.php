<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egresado extends Model
{
    use HasFactory;

    protected $table = 'egresado';
    protected $primaryKey = 'idEgresado';
    
    protected $fillable = [
        'Id_user',
        'Carrera',
        'Matricula',
        'AnioEgreso',
        'Direccion',
        'EmpresaActual',
        'PuestoTrabajo',
        'AniosLaboral',
        'Adicional',
        'SectorEmpresaria',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'Id_user');
    }

    public function Carrera()
    {
        return $this->belongsTo(Carrera::class, 'Carrera');
    }
}
