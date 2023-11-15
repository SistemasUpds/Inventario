<?php

namespace App\Http\Controllers;

use App\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::all();
        return view('departamento', compact('departamentos'));
    }

    /*public function seleccionar(Request $request)
    {
        $sigla = $request->input('departamento');

        // Validar si la sigla del departamento es válida
        if (in_array($sigla, ['PO', 'SU', 'TA', 'LP', 'CB', 'OR', 'SC', 'PA', 'BE'])) {
            // Configurar la conexión de la base de datos según la selección del usuario
            $databaseConnection = "departamento_" . strtolower($sigla);
            config(['database.default' => $databaseConnection]);
            DB::reconnect(); // Reconectar con la base de datos seleccionada
            
            $result = DB::table('estudiante')->get();
            dd($result);
            // Redirige al usuario a una página específica para el departamento seleccionado
            return redirect()->route('login');
        } else {
            // Manejar un caso de selección no válida, por ejemplo, redirigir de nuevo a la página de inicio con un mensaje de error.
            return redirect()->route('inicio')->with('error', 'Departamento no válido');
        }
    }*/
}
