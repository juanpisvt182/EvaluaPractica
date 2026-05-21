<?php


namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'contenido' => ['nullable', 'string', 'max:5000'],
            'archivo' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB
        ]);

        $lastId = Bitacora::max('id') ?? 0;
        $numero = 'BIT-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $archivoPath = null;
        $archivoNombre = null;

        if ($request->hasFile('archivo')) {
            $archivoNombre = $request->file('archivo')->getClientOriginalName();
            $archivoPath = $request->file('archivo')->store('bitacoras');
        }

        Bitacora::create([
            'user_id' => auth()->id(),
            'numero' => $numero,
            'fecha' => $data['fecha'],
            'estado' => $data['estado'],
            'contenido' => $data['contenido'] ?? null,
            'archivo_path' => $archivoPath,
            'archivo_nombre' => $archivoNombre,
        ]);

        return redirect()->route('bitacoras.index')->with('success', 'Bitácora creada correctamente.');
    }

    private function authorizeOwner(Bitacora $bitacora): void
    {
        abort_if($bitacora->user_id !== auth()->id(), 403);
    }

    public function show(Bitacora $bitacora)
    {
        $this->authorizeOwner($bitacora);
        return view('bitacoras.show', compact('bitacora'));
    }

    public function download(Bitacora $bitacora)
    {
        $this->authorizeOwner($bitacora);

        abort_if(!$bitacora->archivo_path, 404);

        return Storage::download(
            $bitacora->archivo_path,
            $bitacora->archivo_nombre ?? basename($bitacora->archivo_path)
        );
    }

    public function destroy(Bitacora $bitacora)
    {
        $this->authorizeOwner($bitacora);

        if ($bitacora->archivo_path) {
            Storage::delete($bitacora->archivo_path);
        }

        $bitacora->delete();

        return redirect()->route('bitacoras.index')->with('success', 'Bitácora eliminada.');
    }
}