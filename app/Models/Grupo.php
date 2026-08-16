<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = [
        'nombre',
        'materia',
        'instructor_id',
        'estado',
    ];

    /**
     * Docente asignado al grupo.
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    /**
 * Estudiantes pertenecientes al grupo.
 */
public function estudiantes()
{
    return $this->belongsToMany(User::class, 'grupo_user')
        ->withTimestamps();
}
/**
 * Evaluaciones asignadas al grupo.
 */
public function evaluaciones()
{
    return $this->hasMany(Evaluacion::class);
}}