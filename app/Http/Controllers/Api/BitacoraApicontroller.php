<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BitacoraApicontroller extends Controller
{
    public function index()
    {
        $bitacoras = Bitacora::orderByDesc('id')->get();

        return response()->json([
            'mensaje' => 'Listado de bitácoras',
            'data' => $bitacoras
        ]);
    }

    public function show(Bitacora $bitacora)
    {
        return response()->json([
            'mensaje' => 'Detalle de la bitácora',
            'data' => $bitacora
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'estado' => ['required', 'in:Borrador,Enviado,Aprobado'],
            'contenido' => ['nullable', 'string', 'max:5000'],
            'archivo' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $lastId = Bitacora::max('id') ?? 0;
        $numero = 'BIT-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $archivoPath = null;
        $archivoNombre = null;

        if ($request->hasFile('archivo')) {
            $archivoNombre = $request->file('archivo')->getClientOriginalName();
            $archivoPath = $request->file('archivo')->store('bitacoras');
        }

        $bitacora = Bitacora::create([
            'user_id' => 1,
            'numero' => $numero,
            'fecha' => $data['fecha'],
            'estado' => $data['estado'],
            'contenido' => $data['contenido'] ?? null,
            'archivo_path' => $archivoPath,
            'archivo_nombre' => $archivoNombre,
        ]);

        return response()->json([
            'mensaje' => 'Bitácora creada correctamente',
            'data' => $bitacora
        ], 201);
    }

    public function destroy(Bitacora $bitacora)
    {
        if ($bitacora->archivo_path) {
            Storage::delete($bitacora->archivo_path);
        }

        $bitacora->delete();

        return response()->json([
            'mensaje' => 'Bitácora eliminada correctamente'
        ]);
    }
}