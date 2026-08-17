<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioApiController extends Controller
{
    /**
     * Listar todos los usuarios.
     */
    public function index()
    {
        $usuarios = User::orderBy('name')->get();

        return response()->json([
            'mensaje' => 'Listado de usuarios',
            'data' => $usuarios
        ], 200);
    }

    /**
     * Mostrar un usuario específico.
     */
    public function show(User $usuario)
    {
        $usuario->load([
            'grupos',
            'gruposComoInstructor',
            'intentos',
        ]);

        return response()->json([
            'mensaje' => 'Detalle del usuario',
            'data' => $usuario
        ], 200);
    }

    /**
     * Cambiar el rol de un usuario.
     */
    public function updateRol(Request $request, User $usuario)
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

        $usuario->update([
            'rol' => $datos['rol'],
        ]);

        return response()->json([
            'mensaje' => 'Rol del usuario actualizado correctamente',
            'data' => $usuario
        ], 200);
    }
}