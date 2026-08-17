<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreguntaApiController extends Controller
{
    /**
     * Listar las preguntas de una evaluación.
     */
    public function index(Evaluacion $evaluacion)
    {
        $preguntas = $evaluacion->preguntas()
            ->with('opciones')
            ->get();

        return response()->json([
            'mensaje' => 'Listado de preguntas',
            'data' => $preguntas
        ], 200);
    }

    /**
     * Crear una pregunta con cuatro opciones.
     */
    public function store(Request $request, Evaluacion $evaluacion)
    {
        $datos = $request->validate([
            'enunciado' => [
                'required',
                'string',
                'max:1000',
            ],

            'opciones' => [
                'required',
                'array',
                'size:4',
            ],

            'opciones.*' => [
                'required',
                'string',
                'max:500',
            ],

            'correcta' => [
                'required',
                'integer',
                'min:0',
                'max:3',
            ],
        ]);

        $pregunta = DB::transaction(function () use ($datos, $evaluacion) {

            $pregunta = $evaluacion->preguntas()->create([
                'enunciado' => $datos['enunciado'],
            ]);

            foreach ($datos['opciones'] as $indice => $texto) {

                $pregunta->opciones()->create([
                    'texto' => $texto,
                    'es_correcta' => $indice === (int) $datos['correcta'],
                ]);
            }

            return $pregunta;
        });

        $pregunta->load('opciones');

        return response()->json([
            'mensaje' => 'Pregunta creada correctamente',
            'data' => $pregunta
        ], 201);
    }

    /**
     * Mostrar una pregunta específica.
     */
    public function show(Pregunta $pregunta)
    {
        $pregunta->load([
            'evaluacion',
            'opciones',
        ]);

        return response()->json([
            'mensaje' => 'Detalle de la pregunta',
            'data' => $pregunta
        ], 200);
    }

    /**
     * Actualizar una pregunta y sus opciones.
     */
    public function update(Request $request, Pregunta $pregunta)
    {
        $datos = $request->validate([
            'enunciado' => [
                'required',
                'string',
                'max:1000',
            ],

            'opciones' => [
                'required',
                'array',
                'size:4',
            ],

            'opciones.*' => [
                'required',
                'string',
                'max:500',
            ],

            'correcta' => [
                'required',
                'integer',
                'min:0',
                'max:3',
            ],
        ]);

        DB::transaction(function () use ($datos, $pregunta) {

            $pregunta->update([
                'enunciado' => $datos['enunciado'],
            ]);

            $opciones = $pregunta->opciones()
                ->orderBy('id')
                ->get();

            foreach ($opciones as $indice => $opcion) {

                $opcion->update([
                    'texto' => $datos['opciones'][$indice],
                    'es_correcta' => $indice === (int) $datos['correcta'],
                ]);
            }
        });

        $pregunta->load('opciones');

        return response()->json([
            'mensaje' => 'Pregunta actualizada correctamente',
            'data' => $pregunta
        ], 200);
    }

    /**
     * Eliminar una pregunta.
     */
    public function destroy(Pregunta $pregunta)
    {
        $pregunta->delete();

        return response()->json([
            'mensaje' => 'Pregunta eliminada correctamente'
        ], 200);
    }
}