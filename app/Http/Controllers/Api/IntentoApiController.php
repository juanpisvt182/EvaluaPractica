<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use App\Models\Intento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class IntentoApiController extends Controller
{
    /**
     * Listar todos los intentos.
     */
    public function index()
    {
        $intentos = Intento::with([
            'usuario',
            'evaluacion',
        ])
            ->latest()
            ->get();

        return response()->json([
            'mensaje' => 'Listado de intentos',
            'data' => $intentos
        ], 200);
    }

    /**
     * Mostrar un intento específico.
     */
    public function show(Intento $intento)
    {
        $intento->load([
            'usuario',
            'evaluacion',
            'respuestas.pregunta',
            'respuestas.opcion',
        ]);

        return response()->json([
            'mensaje' => 'Detalle del intento',
            'data' => $intento
        ], 200);
    }

    /**
     * Iniciar un intento de evaluación.
     */
    public function store(Request $request, Evaluacion $evaluacion)
    {
        $datos = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->where(function ($query) {
                        $query->where('rol', 'aprendiz');
                    }),
            ],
        ]);

        if ($evaluacion->estado !== 'Activa') {
            return response()->json([
                'mensaje' => 'La evaluación no está activa.'
            ], 403);
        }

        if (!$evaluacion->grupo_id) {
            return response()->json([
                'mensaje' => 'La evaluación no tiene un grupo asignado.'
            ], 422);
        }

        $perteneceAlGrupo = $evaluacion->grupo
            ->estudiantes()
            ->where('users.id', $datos['user_id'])
            ->exists();

        if (!$perteneceAlGrupo) {
            return response()->json([
                'mensaje' => 'El aprendiz no pertenece al grupo de esta evaluación.'
            ], 403);
        }

        $totalPreguntas = $evaluacion->preguntas()->count();

        if ($totalPreguntas === 0) {
            return response()->json([
                'mensaje' => 'La evaluación todavía no tiene preguntas.'
            ], 422);
        }

        $intentoFinalizado = Intento::where(
            'user_id',
            $datos['user_id']
        )
            ->where('evaluacion_id', $evaluacion->id)
            ->where('estado', 'Finalizado')
            ->first();

        if ($intentoFinalizado) {
            return response()->json([
                'mensaje' => 'El aprendiz ya finalizó esta evaluación.',
                'data' => $intentoFinalizado
            ], 409);
        }

        $intentoExistente = Intento::where(
            'user_id',
            $datos['user_id']
        )
            ->where('evaluacion_id', $evaluacion->id)
            ->where('estado', 'En progreso')
            ->first();

        if ($intentoExistente) {
            return response()->json([
                'mensaje' => 'El aprendiz ya tiene un intento en progreso.',
                'data' => $intentoExistente
            ], 200);
        }

        $intento = Intento::create([
            'user_id' => $datos['user_id'],
            'evaluacion_id' => $evaluacion->id,
            'total_preguntas' => $totalPreguntas,
            'respuestas_correctas' => 0,
            'puntaje' => 0,
            'estado' => 'En progreso',
        ]);

        return response()->json([
            'mensaje' => 'Intento iniciado correctamente',
            'data' => $intento
        ], 201);
    }

    /**
     * Finalizar un intento y calcular el resultado.
     */
    public function finalizar(Request $request, Intento $intento)
    {
        if ($intento->estado === 'Finalizado') {
            return response()->json([
                'mensaje' => 'Este intento ya fue finalizado.'
            ], 409);
        }

        $intento->load([
            'evaluacion.preguntas.opciones',
        ]);

        $datos = $request->validate([
            'respuestas' => [
                'nullable',
                'array',
            ],

            'respuestas.*' => [
                'integer',
                'exists:opciones,id',
            ],
        ]);

        $respuestas = $datos['respuestas'] ?? [];

        $fechaLimite = $intento->created_at
            ->copy()
            ->addMinutes(
                $intento->evaluacion->tiempo_limite
            );

        $tiempoAgotado = now()
            ->greaterThanOrEqualTo($fechaLimite);

        /*
         * Si todavía hay tiempo, todas las preguntas
         * deben tener respuesta.
         */
        if (!$tiempoAgotado) {

            foreach (
                $intento->evaluacion->preguntas
                as $pregunta
            ) {

                if (!isset($respuestas[$pregunta->id])) {

                    return response()->json([
                        'mensaje' =>
                            'Debes responder todas las preguntas antes de finalizar.'
                    ], 422);
                }
            }
        }

        /*
         * Verificar que cada opción pertenezca
         * realmente a la pregunta indicada.
         */
        foreach (
            $intento->evaluacion->preguntas
            as $pregunta
        ) {

            if (!isset($respuestas[$pregunta->id])) {
                continue;
            }

            $opcionId = (int) $respuestas[$pregunta->id];

            $opcionValida = $pregunta->opciones
                ->firstWhere('id', $opcionId);

            if (!$opcionValida) {

                return response()->json([
                    'mensaje' =>
                        'Una de las opciones seleccionadas no pertenece a su pregunta.'
                ], 422);
            }
        }

        DB::transaction(
            function () use (
                $intento,
                $respuestas
            ) {

                $correctas = 0;

                $total = $intento
                    ->evaluacion
                    ->preguntas
                    ->count();

                foreach (
                    $intento->evaluacion->preguntas
                    as $pregunta
                ) {

                    if (
                        !isset(
                            $respuestas[$pregunta->id]
                        )
                    ) {
                        continue;
                    }

                    $opcionId = (int)
                        $respuestas[$pregunta->id];

                    $opcion = $pregunta
                        ->opciones
                        ->firstWhere(
                            'id',
                            $opcionId
                        );

                    $esCorrecta =
                        $opcion->es_correcta;

                    if ($esCorrecta) {
                        $correctas++;
                    }

                    $intento
                        ->respuestas()
                        ->updateOrCreate(
                            [
                                'pregunta_id' =>
                                    $pregunta->id,
                            ],
                            [
                                'opcion_id' =>
                                    $opcion->id,

                                'es_correcta' =>
                                    $esCorrecta,
                            ]
                        );
                }

                $puntaje = $total > 0
                    ? round(
                        ($correctas / $total) * 100,
                        2
                    )
                    : 0;

                $intento->update([
                    'total_preguntas' => $total,
                    'respuestas_correctas' =>
                        $correctas,
                    'puntaje' => $puntaje,
                    'estado' => 'Finalizado',
                    'finalizado_at' => now(),
                ]);
            }
        );

        $intento->load([
            'evaluacion',
            'respuestas.pregunta',
            'respuestas.opcion',
        ]);

        return response()->json([
            'mensaje' =>
                'Intento finalizado correctamente',
            'data' => $intento
        ], 200);
    }
}