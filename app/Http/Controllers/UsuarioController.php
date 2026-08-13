<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    /**
     * Mostrar todos los usuarios.
     */
    public function index(): View
    {
        $usuarios = User::orderBy('name')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Cambiar el rol de un usuario.
     */
    public function updateRol(Request $request, User $usuario): RedirectResponse
    {
        $datos = $request->validate([
            'rol' => [
                'required',
                Rule::in([
                    'aprendiz',
                    'instructor',
                    'administrador',
                ]),
            ],
        ]);

        // Evitar que el administrador se quite su propio permiso.
        if (
            $usuario->id === auth()->id()
            && $datos['rol'] !== 'administrador'
        ) {
            return back()->withErrors([
                'rol' => 'No puedes quitarte tu propio rol de administrador.',
            ]);
        }

        $usuario->update([
            'rol' => $datos['rol'],
        ]);

        return back()->with(
            'success',
            'Rol de '.$usuario->name.' actualizado correctamente.'
        );
    }
}