<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreguntaController extends Controller
{
    /**
     * Guardar una pregunta con sus cuatro opciones.
     */
    public function store(Request $request, Evaluacion $evaluacion)
    {
        $this->verificarAcceso($evaluacion);

        $datos = $request->validate([
            'enunciado' => 'required|string|max:1000',
            'opciones' => 'required|array|size:4',
            'opciones.*' => 'required|string|max:500',
            'correcta' => 'required|integer|min:0|max:3',
        ]);

        DB::transaction(function () use ($datos, $evaluacion) {

            $pregunta = $evaluacion->preguntas()->create([
                'enunciado' => $datos['enunciado'],
            ]);

            foreach ($datos['opciones'] as $indice => $texto) {

                $pregunta->opciones()->create([
                    'texto' => $texto,
                    'es_correcta' => $indice === (int) $datos['correcta'],
                ]);
            }
        });

        return redirect()
            ->route('evaluacion.show', $evaluacion)
            ->with('success', 'Pregunta agregada correctamente.');
    }
public function edit(Pregunta $pregunta)
{
    $evaluacion = $pregunta->evaluacion;

    $this->verificarAcceso($evaluacion);

    $pregunta->load('opciones');

    return view('preguntas.edit', compact('pregunta', 'evaluacion'));
}

public function update(Request $request, Pregunta $pregunta)
{
    $evaluacion = $pregunta->evaluacion;

    $this->verificarAcceso($evaluacion);

    $datos = $request->validate([
        'enunciado' => 'required|string|max:1000',
        'opciones' => 'required|array|size:4',
        'opciones.*' => 'required|string|max:500',
        'correcta' => 'required|integer|min:0|max:3',
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

    return redirect()
        ->route('evaluacion.show', $evaluacion)
        ->with('success', 'Pregunta actualizada correctamente.');
}
    /**
     * Eliminar una pregunta.
     */
    public function destroy(Pregunta $pregunta)
    {
        $evaluacion = $pregunta->evaluacion;

        $this->verificarAcceso($evaluacion);

        $pregunta->delete();

        return redirect()
            ->route('evaluacion.show', $evaluacion)
            ->with('success', 'Pregunta eliminada correctamente.');
    }

    /**
     * Verificar acceso.
     */
    private function verificarAcceso(Evaluacion $evaluacion)
    {
        $usuario = auth()->user();

        if (
            !$usuario->esAdministrador() &&
            $evaluacion->user_id !== $usuario->id
        ) {
            abort(403);
        }
    }
}