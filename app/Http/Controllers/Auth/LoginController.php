<?php

namespace App\Http\Controllers\Auth;

use App\DepartamentoUser;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    //$databaseName = config('database.connections.' . config('database.default') . '.database');
    //dd($databaseName);


    public function login(Request $request)
    {
        $sigla = $request->input('departamento');

        $userMain = User::where('email', $request->email)->first();
        // Valida si el departamento es válido
        if (in_array($sigla, ['PO', 'SU', 'Tj', 'LP', 'CB', 'OR', 'SC', 'PA', 'BE'])) {
            $databaseConnection = "departamento_" . strtolower($sigla);
            config(['database.default' => $databaseConnection]);
            DB::reconnect();
            //$databaseName = config('database.connections.' . config('database.default') . '.database');
            $userDepartment = User::where('email', $request->email)->first();
            if ($userDepartment) {
                if ($userDepartment->email == $userMain->email && password_verify($request->password, $userDepartment->password) && password_verify($request->password, $userMain->password)) {
                    Auth::loginUsingId($userMain->id);
                    return redirect('/');
                }
                return back()->with('success', 'Credenciales inválidas');
            } else {
                return back()->with('success', 'El correo no existe');
            }
        }
        return back()->with('success', 'Credenciales inválidas o departamento no válido');
    }
}
