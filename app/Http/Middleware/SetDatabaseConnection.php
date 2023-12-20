<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SetDatabaseConnection
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
        $departmentId = $request->user()->department_id;

        $connection = 'department_' . $departmentId;

        // Configurar la conexión de base de datos según el departamento
        Config::set('database.default', $connection);
        DB::purge($connection);
        return $next($request);
    }
}
