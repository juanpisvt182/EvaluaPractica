<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Evaluacion;
use App\Models\Grupo;
use App\Models\Intento;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Administrador
        |--------------------------------------------------------------------------
        */
        if ($usuario->esAdministrador()) {

            $datos = [
                'totalUsuarios' => User::count(),

                'totalEstudiantes' => User::where(
                    'rol',
                    'aprendiz'
                )->count(),

                'totalDocentes' => User::where(
                    'rol',
                    'instructor'
                )->count(),

                'totalGrupos' => Grupo::count(),

                'totalEvaluaciones' => Evaluacion::count(),

                'totalIntentos' => Intento::where(
                    'estado',
                    'Finalizado'
                )->count(),
            ];

            return view(
                'dashboard',
                compact('usuario', 'datos')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Docente
        |--------------------------------------------------------------------------
        */
        if ($usuario->esInstructor()) {

            $grupos = Grupo::where(
                'instructor_id',
                $usuario->id
            );

            $evaluaciones = Evaluacion::where(
                'user_id',
                $usuario->id
            );

            $totalEstudiantes = User::where(
                'rol',
                'aprendiz'
            )
                ->whereHas(
                    'grupos',
                    function ($query) use ($usuario) {
                        $query->where(
                            'instructor_id',
                            $usuario->id
                        );
                    }
                )
                ->distinct()
                ->count();

            $totalIntentos = Intento::whereHas(
                'evaluacion',
                function ($query) use ($usuario) {
                    $query->where(
                        'user_id',
                        $usuario->id
                    );
                }
            )
                ->where('estado', 'Finalizado')
                ->count();

            $datos = [
                'totalGrupos' => $grupos->count(),

                'totalEvaluaciones' => $evaluaciones->count(),

                'evaluacionesActivas' => Evaluacion::where(
                    'user_id',
                    $usuario->id
                )
                    ->where('estado', 'Activa')
                    ->count(),

                'totalEstudiantes' => $totalEstudiantes,

                'totalIntentos' => $totalIntentos,
            ];

            return view(
                'dashboard',
                compact('usuario', 'datos')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Estudiante
        |--------------------------------------------------------------------------
        */

        $grupoIds = $usuario
            ->grupos()
            ->pluck('grupos.id');

        $evaluacionesDisponibles = Evaluacion::whereIn(
            'grupo_id',
            $grupoIds
        )
            ->where('estado', 'Activa')
            ->count();

        $intentosFinalizados = Intento::where(
            'user_id',
            $usuario->id
        )
            ->where('estado', 'Finalizado');

        $promedio = (clone $intentosFinalizados)
            ->avg('puntaje');

        $datos = [
            'totalGrupos' => $grupoIds->count(),

            'evaluacionesDisponibles' =>
                $evaluacionesDisponibles,

            'evaluacionesCompletadas' =>
                $intentosFinalizados->count(),

            'promedio' => $promedio
                ? round($promedio, 1)
                : 0,

            'totalBitacoras' => Bitacora::where(
                'user_id',
                $usuario->id
            )->count(),
        ];

        return view(
            'dashboard',
            compact('usuario', 'datos')
        );
    }
}