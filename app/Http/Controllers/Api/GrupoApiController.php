<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GrupoApiController extends Controller
{
    /**
     * Listar todos los grupos.
     */
    public function index()
    {
        $grupos = Grupo::with('instructor')
            ->withCount('estudiantes')
            ->latest()
            ->get();

        return response()->json([
            'mensaje' => 'Listado de grupos',
            'data' => $grupos
        ], 200);
    }

    /**
     * Crear un grupo.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
            ],

            'materia' => [
                'required',
                'string',
                'max:150',
            ],

            'instructor_id' => [
                'nullable',
                Rule::exists('users', 'id')
                    ->where(function ($query) {
                        $query->where('rol', 'instructor');
                    }),
            ],

            'estado' => [
                'required',
                Rule::in([
                    'Activo',
                    'Inactivo',
                ]),
            ],
        ]);

        $grupo = Grupo::create($datos);

        return response()->json([
            'mensaje' => 'Grupo creado correctamente',
            'data' => $grupo
        ], 201);
    }

    /**
     * Mostrar un grupo.
     */
    public function show(Grupo $grupo)
    {
        $grupo->load([
            'instructor',
            'estudiantes',
            'evaluaciones',
        ]);

        return response()->json([
            'mensaje' => 'Detalle del grupo',
            'data' => $grupo
        ], 200);
    }

    /**
     * Actualizar un grupo.
     */
    public function update(Request $request, Grupo $grupo)
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
            ],

            'materia' => [
                'required',
                'string',
                'max:150',
            ],

            'instructor_id' => [
                'nullable',
                Rule::exists('users', 'id')
                    ->where(function ($query) {
                        $query->where('rol', 'instructor');
                    }),
            ],

            'estado' => [
                'required',
                Rule::in([
                    'Activo',
                    'Inactivo',
                ]),
            ],
        ]);

        $grupo->update($datos);

        return response()->json([
            'mensaje' => 'Grupo actualizado correctamente',
            'data' => $grupo
        ], 200);
    }

    /**
     * Eliminar un grupo.
     */
    public function destroy(Grupo $grupo)
    {
        if ($grupo->evaluaciones()->exists()) {
            return response()->json([
                'mensaje' => 'No se puede eliminar el grupo porque tiene evaluaciones asignadas.'
            ], 409);
        }

        $grupo->delete();

        return response()->json([
            'mensaje' => 'Grupo eliminado correctamente'
        ], 200);
    }

    /**
     * Agregar un aprendiz a un grupo.
     */
    public function agregarEstudiante(Request $request, Grupo $grupo)
    {
        $datos = $request->validate([
            'estudiante_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->where(function ($query) {
                        $query->where('rol', 'aprendiz');
                    }),
            ],
        ]);

        $grupo->estudiantes()->syncWithoutDetaching([
            $datos['estudiante_id'],
        ]);

        $grupo->load('estudiantes');

        return response()->json([
            'mensaje' => 'Estudiante agregado al grupo correctamente',
            'data' => $grupo
        ], 200);
    }

    /**
     * Quitar un aprendiz de un grupo.
     */
    public function quitarEstudiante(Grupo $grupo, User $estudiante)
    {
        $pertenece = $grupo->estudiantes()
            ->where('users.id', $estudiante->id)
            ->exists();

        if (!$pertenece) {
            return response()->json([
                'mensaje' => 'El estudiante no pertenece a este grupo.'
            ], 404);
        }

        $grupo->estudiantes()->detach($estudiante->id);

        return response()->json([
            'mensaje' => 'Estudiante retirado del grupo correctamente'
        ], 200);
    }
}