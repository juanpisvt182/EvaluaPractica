<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use Illuminate\Http\Request;

class EvaluacionApiController extends Controller
{
    /**
     * Listar todas las evaluaciones.
     */
    public function index()
    {
        $evaluaciones = Evaluacion::with(['instructor', 'grupo'])
            ->latest()
            ->get();

        return response()->json([
            'mensaje' => 'Listado de evaluaciones',
            'data' => $evaluaciones
        ], 200);
    }

    /**
     * Crear una nueva evaluación.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'grupo_id' => ['nullable', 'exists:grupos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'tiempo_limite' => ['required', 'integer', 'min:1'],
            'estado' => ['required', 'in:Activa,Inactiva'],
        ]);

        $evaluacion = Evaluacion::create($datos);

        return response()->json([
            'mensaje' => 'Evaluación creada correctamente',
            'data' => $evaluacion
        ], 201);
    }

    /**
     * Mostrar una evaluación específica.
     */
    public function show(Evaluacion $evaluacion)
    {
        $evaluacion->load(['instructor', 'grupo', 'preguntas.opciones']);

        return response()->json([
            'mensaje' => 'Detalle de la evaluación',
            'data' => $evaluacion
        ], 200);
    }

    /**
     * Actualizar una evaluación.
     */
    public function update(Request $request, Evaluacion $evaluacion)
    {
        $datos = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'grupo_id' => ['nullable', 'exists:grupos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'tiempo_limite' => ['required', 'integer', 'min:1'],
            'estado' => ['required', 'in:Activa,Inactiva'],
        ]);

        $evaluacion->update($datos);

        return response()->json([
            'mensaje' => 'Evaluación actualizada correctamente',
            'data' => $evaluacion
        ], 200);
    }

    /**
     * Eliminar una evaluación.
     */
    public function destroy(Evaluacion $evaluacion)
    {
        $evaluacion->delete();

        return response()->json([
            'mensaje' => 'Evaluación eliminada correctamente'
        ], 200);
    }
}