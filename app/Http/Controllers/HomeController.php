<?php

namespace App\Http\Controllers;

use App\Activo;
use App\Area;
use App\Item;
use App\Tipos;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (auth()->user()->admin) {
            $user = User::find(auth()->user()->id);
            $areas = Area::orderBy('sigla', 'asc')->get();
            //$databaseName = config('database.connections.' . config('database.default') . '.database');
            return view('home', compact('areas', 'user'));
        }
        return view('welcome');
    }

    public function sinPermiso() {
        return view('errors.permiso');
    }
    public function reportesActivos() {
        $tipos = Tipos::all();
        $resultados = [];
        return view('reportes', compact('tipos', 'resultados'));
    }
    public function selectActivos(Request $request) {
        $query = $request->input('name');
        $activos = Activo::where('activo', 'like', '%' . $query . '%')
                        //->orWhere('ap_paterno', 'like', '%' . $query . '%')
                        ->get();
        return response()->json($activos);
    }
    
    public function mostrarResultados(Request $request) {
        $tipos = Tipos::all();
        $query1 = $request->input('activo');
        //$query2 = $request->input('tipo');
        $resultados = Item::where('activo_id', $query1)->get();
        $num = 1;
        return view('reportes', compact('tipos', 'resultados', 'num'));
    }
    public function descargarResultados($idActivo) {
        dd('descargar');
        $resultados = Item::where('activo_id', $idActivo)->get();
        return back();
    }
}
