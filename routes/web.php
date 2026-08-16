<?php

use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\IntentoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\DashboardController;
Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)
    ->middleware('auth')
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Perfil
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Bitácoras - Aprendiz
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'rol:aprendiz'])->group(function () {

    Route::get(
        '/bitacoras',
        [BitacoraController::class, 'index']
    )->name('bitacoras.index');


    Route::get(
        '/bitacoras/create',
        [BitacoraController::class, 'create']
    )->name('bitacoras.create');


    Route::post(
        '/bitacoras',
        [BitacoraController::class, 'store']
    )->name('bitacoras.store');


    Route::get(
        '/bitacoras/{bitacora}',
        [BitacoraController::class, 'show']
    )->name('bitacoras.show');


    Route::get(
        '/bitacoras/{bitacora}/download',
        [BitacoraController::class, 'download']
    )->name('bitacoras.download');


    Route::delete(
        '/bitacoras/{bitacora}',
        [BitacoraController::class, 'destroy']
    )->name('bitacoras.destroy');


    /*
    |--------------------------------------------------------------------------
    | Evaluaciones del estudiante
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/mis-evaluaciones',
        [IntentoController::class, 'index']
    )->name('intentos.index');


    Route::get(
        '/mis-resultados',
        [IntentoController::class, 'historial']
    )->name('intentos.historial');


    Route::post(
        '/evaluaciones/{evaluacion}/iniciar',
        [IntentoController::class, 'iniciar']
    )->name('intentos.iniciar');


    Route::get(
        '/intentos/{intento}/presentar',
        [IntentoController::class, 'presentar']
    )->name('intentos.presentar');


    Route::post(
        '/intentos/{intento}/finalizar',
        [IntentoController::class, 'finalizar']
    )->name('intentos.finalizar');


    Route::get(
        '/intentos/{intento}/resultado',
        [IntentoController::class, 'resultado']
    )->name('intentos.resultado');

});


/*
|--------------------------------------------------------------------------
| Revisión de Bitácoras - Docente y administrador
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'rol:instructor,administrador',
])->group(function () {

    Route::get(
        '/revision-bitacoras',
        [BitacoraController::class, 'revisionIndex']
    )->name('bitacoras.revision.index');


    Route::get(
        '/revision-bitacoras/{bitacora}/download',
        [BitacoraController::class, 'revisionDownload']
    )->name('bitacoras.revision.download');


    Route::get(
        '/revision-bitacoras/{bitacora}',
        [BitacoraController::class, 'revisionShow']
    )->name('bitacoras.revision.show');


    Route::patch(
        '/revision-bitacoras/{bitacora}',
        [BitacoraController::class, 'revisar']
    )->name('bitacoras.revision.revisar');

});


/*
|--------------------------------------------------------------------------
| Evaluaciones - Instructor y administrador
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'rol:instructor,administrador'])->group(function () {

    Route::get('/evaluaciones', [EvaluacionController::class, 'index'])
        ->name('evaluacion.index');

    Route::get('/evaluaciones/crear', [EvaluacionController::class, 'create'])
        ->name('evaluacion.create');

    Route::post('/evaluaciones', [EvaluacionController::class, 'store'])
        ->name('evaluacion.store');

    Route::get('/evaluaciones/{evaluacion}', [EvaluacionController::class, 'show'])
        ->name('evaluacion.show');

    Route::get('/evaluaciones/{evaluacion}/editar', [EvaluacionController::class, 'edit'])
        ->name('evaluacion.edit');

    Route::put('/evaluaciones/{evaluacion}', [EvaluacionController::class, 'update'])
        ->name('evaluacion.update');

    Route::delete('/evaluaciones/{evaluacion}', [EvaluacionController::class, 'destroy'])
        ->name('evaluacion.destroy');


    // Resultados de la evaluación
    Route::get('/evaluaciones/{evaluacion}/resultados', [EvaluacionController::class, 'resultados'])
        ->name('evaluacion.resultados');


    // Preguntas
    Route::post('/evaluaciones/{evaluacion}/preguntas', [PreguntaController::class, 'store'])
        ->name('preguntas.store');

    Route::get('/preguntas/{pregunta}/editar', [PreguntaController::class, 'edit'])
        ->name('preguntas.edit');

    Route::put('/preguntas/{pregunta}', [PreguntaController::class, 'update'])
        ->name('preguntas.update');

    Route::delete('/preguntas/{pregunta}', [PreguntaController::class, 'destroy'])
        ->name('preguntas.destroy');

}); 

/*
|--------------------------------------------------------------------------
| Administración de usuarios - Solo administrador
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'rol:administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/usuarios', [UsuarioController::class, 'index'])
            ->name('usuarios.index');

        Route::patch('/usuarios/{usuario}/rol', [UsuarioController::class, 'updateRol'])
            ->name('usuarios.rol');
    });
require __DIR__.'/auth.php';
/*
|--------------------------------------------------------------------------
| Grupos
|--------------------------------------------------------------------------
*/

// Solo el administrador puede crear y administrar grupos.
Route::middleware(['auth', 'rol:administrador'])->group(function () {

    Route::get('/grupos/crear', [GrupoController::class, 'create'])
        ->name('grupos.create');

    Route::post('/grupos', [GrupoController::class, 'store'])
        ->name('grupos.store');

    Route::post(
        '/grupos/{grupo}/estudiantes',
        [GrupoController::class, 'agregarEstudiante']
    )->name('grupos.estudiantes.agregar');

    Route::delete(
        '/grupos/{grupo}/estudiantes/{estudiante}',
        [GrupoController::class, 'quitarEstudiante']
    )->name('grupos.estudiantes.quitar');

});
Route::get('/grupos/{grupo}/editar', [GrupoController::class, 'edit'])
    ->name('grupos.edit');

Route::put('/grupos/{grupo}', [GrupoController::class, 'update'])
    ->name('grupos.update');
    Route::delete('/grupos/{grupo}', [GrupoController::class, 'destroy'])
    ->name('grupos.destroy');


// Usuarios autenticados pueden consultar sus grupos.
Route::middleware('auth')->group(function () {

    Route::get('/grupos', [GrupoController::class, 'index'])
        ->name('grupos.index');

    Route::get('/grupos/{grupo}', [GrupoController::class, 'show'])
        ->name('grupos.show');

});


