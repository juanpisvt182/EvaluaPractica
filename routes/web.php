<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BitacoraController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bitácoras
    Route::get('/bitacoras', [BitacoraController::class, 'index'])->name('bitacoras.index');
    Route::get('/bitacoras/create', [BitacoraController::class, 'create'])->name('bitacoras.create');
    Route::post('/bitacoras', [BitacoraController::class, 'store'])->name('bitacoras.store');
    Route::delete('/bitacoras/{bitacora}', [BitacoraController::class, 'destroy'])->name('bitacoras.destroy');
});

require __DIR__.'/auth.php';