<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intento extends Model
{
    protected $table = 'intentos';

    protected $fillable = [
        'user_id',
        'evaluacion_id',
        'total_preguntas',
        'respuestas_correctas',
        'puntaje',
        'estado',
        'finalizado_at',
    ];

    protected $casts = [
        'puntaje' => 'decimal:2',
        'finalizado_at' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class, 'evaluacion_id');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'intento_id');
    }
}