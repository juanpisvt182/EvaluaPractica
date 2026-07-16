<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BitacoraApicontroller;

Route::get('/bitacoras', [BitacoraApicontroller::class, 'index']);
Route::post('/bitacoras', [BitacoraApicontroller::class, 'store']);
Route::get('/bitacoras/{bitacora}', [BitacoraApicontroller::class, 'show']);
Route::delete('/bitacoras/{bitacora}', [BitacoraApicontroller::class, 'destroy']);