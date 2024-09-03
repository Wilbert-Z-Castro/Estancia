<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvOfertas extends Model
{
    use HasFactory;

    protected $table = 'cv_ofertas';
    protected $primaryKey = 'idCVOferta';
    protected $fillable = [
        'Id_oferta',
        'Cv',
        'id_egresado',
    ];

    public function oferta()
    {
        return $this->belongsTo(OfertaTrabajo::class, 'Id_oferta');
    }

    public function egresado()
    {
        return $this->belongsTo(Egresado::class, 'id_egresado');
    }
}
