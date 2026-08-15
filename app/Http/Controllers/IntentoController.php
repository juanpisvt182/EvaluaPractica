<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Intento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IntentoController extends Controller
{
    /**
     * Mostrar evaluaciones activas para el aprendiz.
     */
    public function index()
    {
        $evaluaciones = Evaluacion::with('instructor')
            ->withCount('preguntas')
            ->where('estado', 'Activa')
            ->latest()
            ->get();

        return view('intentos.index', compact('evaluaciones'));
    }

    /**
     * Iniciar una evaluación.
     */
    public function iniciar(Evaluacion $evaluacion)
    {
        if ($evaluacion->estado !== 'Activa') {
            abort(403, 'Esta evaluación no está disponible.');
        }

        if ($evaluacion->preguntas()->count() === 0) {
            return redirect()
                ->route('intentos.index')
                ->with('error', 'Esta evaluación todavía no tiene preguntas.');
        }

        $intento = Intento::where('user_id', auth()->id())
            ->where('evaluacion_id', $evaluacion->id)
            ->where('estado', 'En progreso')
            ->first();

        if (!$intento) {
            $intento = Intento::create([
                'user_id' => auth()->id(),
                'evaluacion_id' => $evaluacion->id,
                'total_preguntas' => $evaluacion->preguntas()->count(),
                'respuestas_correctas' => 0,
                'puntaje' => 0,
                'estado' => 'En progreso',
            ]);
        }

        return redirect()
            ->route('intentos.presentar', $intento);
    }

    /**
     * Mostrar la evaluación al aprendiz.
     */
    public function presentar(Intento $intento)
    {
        $this->verificarPropietario($intento);

        if ($intento->estado === 'Finalizado') {
            return redirect()
                ->route('intentos.resultado', $intento);
        }

        $intento->load([
            'evaluacion.preguntas.opciones'
        ]);

        return view('intentos.presentar', compact('intento'));
    }

    /**
     * Guardar las respuestas y calcular el resultado.
     */
    public function finalizar(Request $request, Intento $intento)
    {
        $this->verificarPropietario($intento);

        if ($intento->estado === 'Finalizado') {
            return redirect()
                ->route('intentos.resultado', $intento);
        }

        $intento->load([
            'evaluacion.preguntas.opciones'
        ]);

        $datos = $request->validate([
            'respuestas' => 'required|array',
            'respuestas.*' => 'required|integer|exists:opciones,id',
        ]);

        // Comprobar que todas las preguntas tengan respuesta.
        foreach ($intento->evaluacion->preguntas as $pregunta) {
            if (!isset($datos['respuestas'][$pregunta->id])) {
                return back()
                    ->withErrors([
                        'respuestas' => 'Debes responder todas las preguntas antes de finalizar.'
                    ])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($datos, $intento) {

            $correctas = 0;
            $total = $intento->evaluacion->preguntas->count();

            foreach ($intento->evaluacion->preguntas as $pregunta) {

                $opcionId = (int) $datos['respuestas'][$pregunta->id];

                // Comprobar que la opción realmente pertenezca a esta pregunta.
                $opcion = $pregunta->opciones
                    ->firstWhere('id', $opcionId);

                if (!$opcion) {
                    abort(422, 'La opción seleccionada no pertenece a la pregunta.');
                }

                $esCorrecta = $opcion->es_correcta;

                if ($esCorrecta) {
                    $correctas++;
                }

                $intento->respuestas()->updateOrCreate(
                    [
                        'pregunta_id' => $pregunta->id,
                    ],
                    [
                        'opcion_id' => $opcion->id,
                        'es_correcta' => $esCorrecta,
                    ]
                );
            }

            $puntaje = $total > 0
                ? round(($correctas / $total) * 100, 2)
                : 0;

            $intento->update([
                'total_preguntas' => $total,
                'respuestas_correctas' => $correctas,
                'puntaje' => $puntaje,
                'estado' => 'Finalizado',
                'finalizado_at' => now(),
            ]);
        });

        return redirect()
            ->route('intentos.resultado', $intento);
    }

    /**
     * Mostrar el resultado.
     */
    public function resultado(Intento $intento)
    {
        $this->verificarPropietario($intento);

        if ($intento->estado !== 'Finalizado') {
            return redirect()
                ->route('intentos.presentar', $intento);
        }

        $intento->load([
            'evaluacion',
            'respuestas.pregunta',
            'respuestas.opcion',
        ]);

        return view('intentos.resultado', compact('intento'));
    }

    /**
     * Evitar que un aprendiz vea intentos de otro usuario.
     */
    private function verificarPropietario(Intento $intento)
    {
        if ($intento->user_id !== auth()->id()) {
            abort(403);
        }
    }
}