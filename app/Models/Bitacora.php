<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $fillable = [
        'user_id',
        'numero',
        'fecha',
        'estado',
        'archivo_path',
        'contenido',
        'archivo_nombre',
    ];
}
