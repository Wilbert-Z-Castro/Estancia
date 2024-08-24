<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ofertaCarrera extends Model
{
    use HasFactory;
    protected $table = 'ofertacarrera';
    protected $primaryKey = 'idOfertaCarrera';

    protected $fillable = [
        'idoferta',
        'idcarrera',
    ];

    public function oferta()
    {
        return $this->belongsTo(OfertaTrabajo::class, 'idoferta');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'idcarrera');
    }

    public function ofertaTrabajo()
    {
        return $this->belongsTo(OfertaTrabajo::class, 'idOfertaTrabajo', 'idOfertaTrabajo');
    }

    // Opcional: relación inversa a Carrera
    public function carrera2()
    {
        return $this->belongsTo(Carrera::class, 'idCarrera', 'idCarrera');
    }

}
