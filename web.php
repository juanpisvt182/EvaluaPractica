<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BitacoraController;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $userId = auth()->id();

    $total = Bitacora::where('user_id', $userId)->count();
    $aprobadas = Bitacora::where('user_id', $userId)->where('estado', 'Aprobado')->count();
    $enviadas = Bitacora::where('user_id', $userId)->where('estado', 'Enviado')->count();
    $borrador = Bitacora::where('user_id', $userId)->where('estado', 'Borrador')->count();

    $pendientes = $borrador + $enviadas;
    $progreso = $total > 0 ? round(($aprobadas / $total) * 100) : 0;

    return view('dashboard', compact('total', 'aprobadas', 'pendientes', 'progreso', 'enviadas', 'borrador'));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bitácoras
    Route::get('/bitacoras', [BitacoraController::class, 'index'])->name('bitacoras.index');
    Route::get('/bitacoras/create', [BitacoraController::class, 'create'])->name('bitacoras.create');
    Route::post('/bitacoras', [BitacoraController::class, 'store'])->name('bitacoras.store');

    Route::get('/bitacoras/{bitacora}', [BitacoraController::class, 'show'])->name('bitacoras.show');
    Route::get('/bitacoras/{bitacora}/download', [BitacoraController::class, 'download'])->name('bitacoras.download');

    Route::delete('/bitacoras/{bitacora}', [BitacoraController::class, 'destroy'])->name('bitacoras.destroy');
});

use App\Http\Controllers\EvaluacionController;

Route::get('/evaluacion/crear', [EvaluacionController::class, 'create'])->name('evaluacion.create');
Route::post('/evaluacion/guardar', [EvaluacionController::class, 'store'])->name('evaluacion.store');

require __DIR__ . '/auth.php';
