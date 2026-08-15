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

    /**
     * Instructor que creó la evaluación.
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Preguntas pertenecientes a la evaluación.
     */
    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'evaluacion_id');
    }
}