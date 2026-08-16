<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GrupoController extends Controller
{
    /**
     * Mostrar los grupos según el usuario autenticado.
     */
    public function index()
    {
        $usuario = auth()->user();

        $consulta = Grupo::with('instructor')
            ->withCount('estudiantes');

        // Administrador: ve todos los grupos.
        if ($usuario->esAdministrador()) {
            $grupos = $consulta
                ->latest()
                ->get();

        // Instructor: solamente sus grupos.
        } elseif ($usuario->esInstructor()) {
            $grupos = $consulta
                ->where('instructor_id', $usuario->id)
                ->latest()
                ->get();

        // Aprendiz: solamente grupos donde está inscrito.
        } else {
            $grupos = $consulta
                ->whereHas('estudiantes', function ($query) use ($usuario) {
                    $query->where('users.id', $usuario->id);
                })
                ->latest()
                ->get();
        }

        return view('grupos.index', compact('grupos'));
    }

    /**
     * Mostrar formulario para crear un grupo.
     */
    public function create()
    {
        $instructores = User::where('rol', 'instructor')
            ->orderBy('name')
            ->get();

        return view('grupos.create', compact('instructores'));
    }

    /**
     * Guardar un nuevo grupo.
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
                Rule::exists('users', 'id')->where(function ($query) {
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

        Grupo::create($datos);

        return redirect()
            ->route('grupos.index')
            ->with('success', 'Grupo creado correctamente.');
    }
    /**
 * Mostrar formulario para editar un grupo.
 */
public function edit(Grupo $grupo)
{
    $instructores = User::where('rol', 'instructor')
        ->orderBy('name')
        ->get();

    return view(
        'grupos.edit',
        compact(
            'grupo',
            'instructores'
        )
    );
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
            Rule::exists('users', 'id')->where(function ($query) {
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

    return redirect()
        ->route('grupos.show', $grupo)
        ->with('success', 'Grupo actualizado correctamente.');
}
/**
 * Eliminar un grupo.
 */
public function destroy(Grupo $grupo)
{
    // No permitir eliminar grupos que todavía tengan evaluaciones.
    if ($grupo->evaluaciones()->exists()) {
        return redirect()
            ->route('grupos.index')
            ->with(
                'error',
                'No puedes eliminar este grupo porque tiene evaluaciones asignadas.'
            );
    }

    $grupo->delete();

    return redirect()
        ->route('grupos.index')
        ->with('success', 'Grupo eliminado correctamente.');
}
/**
 * Agregar un estudiante al grupo.
 */
public function agregarEstudiante(Request $request, Grupo $grupo)
{
    $datos = $request->validate([
        'estudiante_id' => [
            'required',
            Rule::exists('users', 'id')->where(function ($query) {
                $query->where('rol', 'aprendiz');
            }),
        ],
    ]);

    $grupo->estudiantes()->syncWithoutDetaching([
        $datos['estudiante_id'],
    ]);

    return redirect()
        ->route('grupos.show', $grupo)
        ->with('success', 'Estudiante agregado al grupo correctamente.');
}


/**
 * Quitar un estudiante del grupo.
 */
public function quitarEstudiante(Grupo $grupo, User $estudiante)
{
    $grupo->estudiantes()
        ->detach($estudiante->id);

    return redirect()
        ->route('grupos.show', $grupo)
        ->with('success', 'Estudiante retirado del grupo correctamente.');
}
/**
 * Mostrar el detalle de un grupo.
 */
public function show(Grupo $grupo)
{
    $usuario = auth()->user();

    // Administrador: puede ver cualquier grupo.
    if ($usuario->esAdministrador()) {
        // Permitido.

    // Instructor: solamente puede ver sus propios grupos.
    } elseif ($usuario->esInstructor()) {

        if ($grupo->instructor_id !== $usuario->id) {
            abort(403);
        }

    // Estudiante: solamente puede ver grupos donde esté inscrito.
    } else {

        $pertenece = $grupo->estudiantes()
            ->where('users.id', $usuario->id)
            ->exists();

        if (!$pertenece) {
            abort(403);
        }
    }

    $grupo->load([
        'instructor',
        'estudiantes',
    ]);

    // Estudiantes que todavía no pertenecen al grupo.
    $estudiantesDisponibles = collect();

    if ($usuario->esAdministrador()) {

        $idsInscritos = $grupo->estudiantes
            ->pluck('id');

        $estudiantesDisponibles = User::where('rol', 'aprendiz')
            ->whereNotIn('id', $idsInscritos)
            ->orderBy('name')
            ->get();
    }

    return view(
        'grupos.show',
        compact(
            'grupo',
            'estudiantesDisponibles'
        )
    );

}}