<?php

namespace App\Http\Controllers;

use App\Area;
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
}
