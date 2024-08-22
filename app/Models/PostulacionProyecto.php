<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostulacionProyecto extends Model
{
    use HasFactory;

    protected $table = 'PostulacionProyecto';

    protected $fillable = [
        'Id_proyecto',
        'Mensaje',
        'Cv',
        'id_user',
        'Estado',
    ];

    public function proyecto()
    {
        return $this->belongsTo(ProyectoColab::class, 'Id_proyecto');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
