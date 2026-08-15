<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    /**
     * Mostrar el listado de evaluaciones.
     */
    public function index()
    {
        $usuario = auth()->user();

        // El administrador puede ver todas las evaluaciones.
        if ($usuario->esAdministrador()) {
            $evaluaciones = Evaluacion::with('instructor')
                ->latest()
                ->get();
        } else {
            // El instructor solo ve sus propias evaluaciones.
            $evaluaciones = Evaluacion::with('instructor')
                ->where('user_id', $usuario->id)
                ->latest()
                ->get();
        }

        return view('evaluaciones.index', compact('evaluaciones'));
    }

    /**
     * Mostrar el formulario para crear una evaluación.
     */
    public function create()
    {
        return view('evaluaciones.create');
    }

    /**
     * Guardar una nueva evaluación.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tiempo_limite' => 'required|integer|min:1',
        ]);

        Evaluacion::create([
            'user_id' => auth()->id(),
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'] ?? null,
            'tiempo_limite' => $datos['tiempo_limite'],
            'estado' => 'Activa',
        ]);

        return redirect()
            ->route('evaluacion.index')
            ->with('success', 'Evaluación creada correctamente.');
    }

    /**
     * Mostrar una evaluación.
     */
  public function show(Evaluacion $evaluacion)
{
    $this->verificarAcceso($evaluacion);

    $evaluacion->load([
        'instructor',
        'preguntas.opciones'
    ]);

    return view('evaluaciones.show', compact('evaluacion'));
}
    /**
     * Mostrar el formulario para editar.
     */
    public function edit(Evaluacion $evaluacion)
    {
        $this->verificarAcceso($evaluacion);

        return view('evaluaciones.edit', compact('evaluacion'));
    }

    /**
     * Actualizar una evaluación.
     */
    public function update(Request $request, Evaluacion $evaluacion)
    {
        $this->verificarAcceso($evaluacion);

        $datos = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tiempo_limite' => 'required|integer|min:1',
            'estado' => 'required|in:Activa,Inactiva',
        ]);

        $evaluacion->update($datos);

        return redirect()
            ->route('evaluacion.index')
            ->with('success', 'Evaluación actualizada correctamente.');
    }

    /**
     * Eliminar una evaluación.
     */
    public function destroy(Evaluacion $evaluacion)
    {
        $this->verificarAcceso($evaluacion);

        $evaluacion->delete();

        return redirect()
            ->route('evaluacion.index')
            ->with('success', 'Evaluación eliminada correctamente.');
    }

    /**
     * Evitar que un instructor manipule evaluaciones de otro instructor.
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