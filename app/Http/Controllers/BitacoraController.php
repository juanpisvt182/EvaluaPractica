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
    $grupos = auth()->user()
        ->grupos()
        ->where('estado', 'Activo')
        ->orderBy('nombre')
        ->get();

    return view(
        'bitacoras.create',
        compact('grupos')
    );
}
    public function store(Request $request)
    {
        $data = $request->validate([
    'grupo_id' => ['required', 'exists:grupos,id'],
    'fecha' => ['required', 'date'],
    'estado' => ['required', 'in:Borrador,Enviado'],
    'contenido' => ['nullable', 'string', 'max:5000'],
    'archivo' => [
        'nullable',
        'file',
        'mimes:pdf,doc,docx',
        'max:5120',
    ],
]);
$perteneceAlGrupo = auth()->user()
    ->grupos()
    ->where('grupos.id', $data['grupo_id'])
    ->where('grupos.estado', 'Activo')
    ->exists();

if (!$perteneceAlGrupo) {
    abort(403);
}
if (!$perteneceAlGrupo) {
    abort(403);
}

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
             'grupo_id' => $data['grupo_id'],
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
/**
 * Mostrar las bitácoras que puede revisar el docente.
 */
public function revisionIndex()
{
    $usuario = auth()->user();

    $consulta = Bitacora::with([
        'estudiante',
        'grupo',
        'revisor',
    ])
        ->whereNotNull('grupo_id')
        ->whereIn('estado', [
            'Enviado',
            'Aprobado',
            'Rechazado',
        ]);

    // El administrador puede ver todas.
    if (!$usuario->esAdministrador()) {

        // El docente solo ve bitácoras de sus grupos.
        $consulta->whereHas(
            'grupo',
            function ($query) use ($usuario) {
                $query->where(
                    'instructor_id',
                    $usuario->id
                );
            }
        );
    }

    $bitacoras = $consulta
        ->orderByDesc('fecha')
        ->get();

    return view(
        'bitacoras.revision.index',
        compact('bitacoras')
    );
}


/**
 * Ver una bitácora para revisarla.
 */
public function revisionShow(Bitacora $bitacora)
{
    $this->authorizeReviewer($bitacora);

    if ($bitacora->estado === 'Borrador') {
        abort(403);
    }

    $bitacora->load([
        'estudiante',
        'grupo',
        'revisor',
    ]);

    return view(
        'bitacoras.revision.show',
        compact('bitacora')
    );
}


/**
 * Aprobar o rechazar una bitácora.
 */
public function revisar(
    Request $request,
    Bitacora $bitacora
) {
    $this->authorizeReviewer($bitacora);

    if ($bitacora->estado === 'Borrador') {
        abort(403);
    }

    $datos = $request->validate([
        'estado' => [
            'required',
            'in:Aprobado,Rechazado',
        ],

        'retroalimentacion' => [
            'nullable',
            'string',
            'max:3000',
            'required_if:estado,Rechazado',
        ],
    ]);

    $bitacora->update([
        'estado' => $datos['estado'],

        'retroalimentacion' =>
            $datos['retroalimentacion'] ?? null,

        'revisor_id' => auth()->id(),

        'revisado_at' => now(),
    ]);

    return redirect()
        ->route('bitacoras.revision.index')
        ->with(
            'success',
            'Bitácora revisada correctamente.'
        );
}


/**
 * Descargar archivo como docente o administrador.
 */
public function revisionDownload(Bitacora $bitacora)
{
    $this->authorizeReviewer($bitacora);

    abort_if(!$bitacora->archivo_path, 404);

    return Storage::download(
        $bitacora->archivo_path,
        $bitacora->archivo_nombre
            ?? basename($bitacora->archivo_path)
    );
}


/**
 * Comprobar que el docente puede revisar la bitácora.
 */
private function authorizeReviewer(Bitacora $bitacora): void
{
    $usuario = auth()->user();

    if ($usuario->esAdministrador()) {
        return;
    }

    $puedeRevisar = $bitacora->grupo()
        ->where(
            'instructor_id',
            $usuario->id
        )
        ->exists();

    abort_unless($puedeRevisar, 403);
}
    }