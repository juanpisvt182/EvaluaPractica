<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index()
    {
        $bitacoras = Bitacora::where('user_id', auth()->id())
            ->orderByDesc('fecha')
            ->get();

        return view('bitacoras.index', compact('bitacoras'));
    }

    public function create()
    {
        return view('bitacoras.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'estado' => ['required', 'in:Borrador,Enviado,Aprobado'],
        ]);

        // Número simple BIT-001, BIT-002...
        $lastId = Bitacora::max('id') ?? 0;
        $numero = 'BIT-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        Bitacora::create([
            'user_id' => auth()->id(),
            'numero' => $numero,
            'fecha' => $data['fecha'],
            'estado' => $data['estado'],
        ]);

        return redirect()->route('bitacoras.index')->with('success', 'Bitácora creada correctamente.');
    }

    public function destroy(Bitacora $bitacora)
    {
        // Seguridad: solo borra sus propias bitácoras
        abort_if($bitacora->user_id !== auth()->id(), 403);

        $bitacora->delete();

        return redirect()->route('bitacoras.index')->with('success', 'Bitácora eliminada.');
    }
}