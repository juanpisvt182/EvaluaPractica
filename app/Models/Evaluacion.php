<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluacions'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'tiempo_limite',
        'estado',
    ];

    // Relación: Una evaluación pertenece a un instructor (Usuario)
    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
