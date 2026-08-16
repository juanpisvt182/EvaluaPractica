<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluacions';

    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'tiempo_limite',
        'estado',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'evaluacion_id');
    }

    public function intentos()
    {
        return $this->hasMany(Intento::class, 'evaluacion_id');
    }
}