<?php

use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Route;

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
});


/*
|--------------------------------------------------------------------------
| Evaluaciones - Instructor y administrador
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'rol:instructor,administrador'])->group(function () {

    Route::get('/evaluacion/crear', [EvaluacionController::class, 'create'])
        ->name('evaluacion.create');

    Route::post('/evaluacion/guardar', [EvaluacionController::class, 'store'])
        ->name('evaluacion.store');
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