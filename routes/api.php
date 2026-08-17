<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BitacoraApicontroller;
use App\Http\Controllers\Api\EvaluacionApiController;
use App\Http\Controllers\Api\GrupoApiController;
use App\Http\Controllers\Api\UsuarioApiController;
use App\Http\Controllers\Api\PreguntaApiController;
use App\Http\Controllers\Api\IntentoApiController;


/*
|--------------------------------------------------------------------------
| API Bitácoras
|--------------------------------------------------------------------------
*/

Route::get(
    '/bitacoras',
    [BitacoraApicontroller::class, 'index']
);

Route::post(
    '/bitacoras',
    [BitacoraApicontroller::class, 'store']
);

Route::get(
    '/bitacoras/{bitacora}',
    [BitacoraApicontroller::class, 'show']
);

Route::delete(
    '/bitacoras/{bitacora}',
    [BitacoraApicontroller::class, 'destroy']
);


/*
|--------------------------------------------------------------------------
| API Evaluaciones
|--------------------------------------------------------------------------
*/

Route::get(
    '/evaluaciones',
    [EvaluacionApiController::class, 'index']
);

Route::post(
    '/evaluaciones',
    [EvaluacionApiController::class, 'store']
);

Route::get(
    '/evaluaciones/{evaluacion}',
    [EvaluacionApiController::class, 'show']
);

Route::put(
    '/evaluaciones/{evaluacion}',
    [EvaluacionApiController::class, 'update']
);

Route::delete(
    '/evaluaciones/{evaluacion}',
    [EvaluacionApiController::class, 'destroy']
);


/*
|--------------------------------------------------------------------------
| API Grupos
|--------------------------------------------------------------------------
*/

Route::get(
    '/grupos',
    [GrupoApiController::class, 'index']
);

Route::post(
    '/grupos',
    [GrupoApiController::class, 'store']
);

Route::get(
    '/grupos/{grupo}',
    [GrupoApiController::class, 'show']
);

Route::put(
    '/grupos/{grupo}',
    [GrupoApiController::class, 'update']
);

Route::delete(
    '/grupos/{grupo}',
    [GrupoApiController::class, 'destroy']
);

Route::post(
    '/grupos/{grupo}/estudiantes',
    [GrupoApiController::class, 'agregarEstudiante']
);

Route::delete(
    '/grupos/{grupo}/estudiantes/{estudiante}',
    [GrupoApiController::class, 'quitarEstudiante']
);


/*
|--------------------------------------------------------------------------
| API Usuarios
|--------------------------------------------------------------------------
*/

Route::get(
    '/usuarios',
    [UsuarioApiController::class, 'index']
);

Route::get(
    '/usuarios/{usuario}',
    [UsuarioApiController::class, 'show']
);

Route::patch(
    '/usuarios/{usuario}/rol',
    [UsuarioApiController::class, 'updateRol']
);


/*
|--------------------------------------------------------------------------
| API Preguntas
|--------------------------------------------------------------------------
*/

Route::get(
    '/evaluaciones/{evaluacion}/preguntas',
    [PreguntaApiController::class, 'index']
);

Route::post(
    '/evaluaciones/{evaluacion}/preguntas',
    [PreguntaApiController::class, 'store']
);

Route::get(
    '/preguntas/{pregunta}',
    [PreguntaApiController::class, 'show']
);

Route::put(
    '/preguntas/{pregunta}',
    [PreguntaApiController::class, 'update']
);

Route::delete(
    '/preguntas/{pregunta}',
    [PreguntaApiController::class, 'destroy']
);


/*
|--------------------------------------------------------------------------
| API Intentos y resultados
|--------------------------------------------------------------------------
*/

Route::get(
    '/intentos',
    [IntentoApiController::class, 'index']
);

Route::get(
    '/intentos/{intento}',
    [IntentoApiController::class, 'show']
);

Route::post(
    '/evaluaciones/{evaluacion}/intentos',
    [IntentoApiController::class, 'store']
);

Route::post(
    '/intentos/{intento}/finalizar',
    [IntentoApiController::class, 'finalizar']
);