<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolMiddleware
{
    /**
     * Verifica que el usuario tenga uno de los roles permitidos.
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {

        $user = $request->user();

        
        $rolesPermitidos = [];

        foreach ($roles as $rol) {

            foreach (explode(',', $rol) as $rolIndividual) {

                $rolIndividual = trim($rolIndividual);

                if ($rolIndividual !== '') {
                    $rolesPermitidos[] = $rolIndividual;
                }
            }
        }

        if (
            !$user ||
            !$user->tieneRol(...$rolesPermitidos)
        ) {
            abort(
                403,
                'No tienes permiso para acceder a esta sección.'
            );
        }

        return $next($request);
    }
}