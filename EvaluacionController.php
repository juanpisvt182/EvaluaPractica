<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\User;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    // Método GET: Muestra el formulario
    public function create()
    {
        $instructores = User::all();
        return view('evaluaciones.create', compact('instructores'));
    }

    // Método POST: Guarda los datos
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tiempo_limite' => 'required|integer|min:1',
        ]);

        Evaluacion::create([
            'user_id' => $request->input('user_id'),
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'tiempo_limite' => $request->input('tiempo_limite'),
            'estado' => 'Activa',
        ]);

        return redirect()->route('evaluacion.create')->with('exito', '¡Cuestionario creado con éxito!');
    }
}
