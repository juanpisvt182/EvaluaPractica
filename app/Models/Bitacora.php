<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $fillable = [
        'user_id',
        'grupo_id',
        'numero',
        'fecha',
        'estado',
        'contenido',
        'archivo_path',
        'archivo_nombre',
        'revisor_id',
        'retroalimentacion',
        'revisado_at',
    ];

    protected $casts = [
        'fecha' => 'date',
        'revisado_at' => 'datetime',
    ];

    /**
     * Estudiante dueño de la bitácora.
     */
    public function estudiante()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Grupo/Materia de la bitácora.
     */
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    /**
     * Docente o administrador que revisó la bitácora.
     */
    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisor_id');
    }
}