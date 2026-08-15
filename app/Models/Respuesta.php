<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $table = 'respuestas';

    protected $fillable = [
        'intento_id',
        'pregunta_id',
        'opcion_id',
        'es_correcta',
    ];

    protected $casts = [
        'es_correcta' => 'boolean',
    ];

    public function intento()
    {
        return $this->belongsTo(Intento::class, 'intento_id');
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'pregunta_id');
    }

    public function opcion()
    {
        return $this->belongsTo(Opcion::class, 'opcion_id');
    }
}