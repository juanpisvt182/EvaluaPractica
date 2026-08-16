<?php

use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\IntentoController;
Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $userId = auth()->id();

    $total = Bitacora::where('user_id', $userId)->count();

    $aprobadas = Bitacora::where('user_id', $userId)
        ->where('estado', 'Aprobado')
        ->count();

    $enviadas = Bitacora::where('user_id', $userId)
        ->where('estado', 'Enviado')
        ->count();

    $borrador = Bitacora::where('user_id', $userId)
        ->where('estado', 'Borrador')
        ->count();

    $pendientes = $borrador + $enviadas;

    $progreso = $total > 0
        ? round(($aprobadas / $total) * 100)
        : 0;

    return view('dashboard', compact(
        'total',
        'aprobadas',
        'pendientes',
        'progreso',
        'enviadas',
        'borrador'
    ));
})->middleware('auth')->name('dashboard');


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

    Route::get('/bitacoras', [BitacoraController::class, 'index'])
        ->name('bitacoras.index');

    Route::get('/bitacoras/create', [BitacoraController::class, 'create'])
        ->name('bitacoras.create');

    Route::post('/bitacoras', [BitacoraController::class, 'store'])
        ->name('bitacoras.store');

    Route::get('/bitacoras/{bitacora}', [BitacoraController::class, 'show'])
        ->name('bitacoras.show');

    Route::get('/bitacoras/{bitacora}/download', [BitacoraController::class, 'download'])
        ->name('bitacoras.download');

    Route::delete('/bitacoras/{bitacora}', [BitacoraController::class, 'destroy'])
        ->name('bitacoras.destroy');


    // Evaluaciones del aprendiz
    Route::get('/mis-evaluaciones', [IntentoController::class, 'index'])
        ->name('intentos.index');

    Route::get('/mis-resultados', [IntentoController::class, 'historial'])
        ->name('intentos.historial');

    Route::post('/evaluaciones/{evaluacion}/iniciar', [IntentoController::class, 'iniciar'])
        ->name('intentos.iniciar');

    Route::get('/intentos/{intento}/presentar', [IntentoController::class, 'presentar'])
        ->name('intentos.presentar');

    Route::post('/intentos/{intento}/finalizar', [IntentoController::class, 'finalizar'])
        ->name('intentos.finalizar');

    Route::get('/intentos/{intento}/resultado', [IntentoController::class, 'resultado'])
        ->name('intentos.resultado');
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