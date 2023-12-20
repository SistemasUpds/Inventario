<?php

namespace App\Http\Middleware;

use App\Actividad;
use App\Material;
use App\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\DB;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        // Registra la actividad del usuario
        Actividad::create([
            'user_id' => auth()->id(), // ID del usuario actual
            'action' => $request->getMethod(), // Método de la solicitud (GET, POST, etc.)
            'description' => self::getDescription($request), // URL completa de la solicitud
        ]);
        return $response;
    }

    private static function getDescription($request)
    {
        $usuario = auth()->user()->name;
        $description = "URL: " . $request->fullUrl();
        // Analizar la solicitud y los datos para determinar la acción
        if ($request->isMethod('POST')) {
            if (strpos($request->path(), 'edit') !== false) {
                $description .= " (El usuarios ". $usuario ." a editado)";
            } else {
                $description .= " (El usuariso ". $usuario ." a agregado)";
            }
        } elseif ($request->isMethod('DELETE')) {
            $description .= " (El usuario ". $usuario ." a eliminado)";
        }
        return $description;
    }
}
