<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $fillable = [
        'evaluacion_id',
        'enunciado',
    ];

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class, 'evaluacion_id');
    }

    public function opciones()
    {
        return $this->hasMany(Opcion::class, 'pregunta_id');
    }
}