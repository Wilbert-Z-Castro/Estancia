<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirCarrera extends Model
{
    use HasFactory;

    protected $table = 'dir_carrera';
    protected $primaryKey = 'idDirCarrera';

    protected $fillable = [
        'Descripcion',
        'AnioInstitucion',
        'id_userDir',
        'FechaAsignacion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_userDir');
    }

    public function carreras()
    {
        return $this->hasMany(Carrera::class, 'idDirCarrera');
    }
}
