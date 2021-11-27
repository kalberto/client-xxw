<?php

namespace App\Http\Controllers\Auth;

use App\Model\Administrador;
use App\Model\Configuracao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    protected function guard()
    {
        return Auth::guard('admin');
    }
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
    protected $redirectTo = '/admin';

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

	/**
    * The user has been authenticated.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  mixed  $user
    * @return mixed
    */
	protected function authenticated(Request $request, $user)
	{
		Administrador::login($user->id, $request);
	}

	public function showLoginForm() {
		$configuracao = Configuracao::with('complementos')->first();
		return view('admin.auth.login',compact("configuracao"));
	}

    public function refreshToken(Request $request)
	{
		session()->regenerate();
		return response()->json([
			"csrf_token"=>csrf_token()],
		200);
	}

	/**
    * Log the user out of the application.
    *
    * @param \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
	public function logout( Request $request ) {
		$this->guard()->logout();
		$request->session()->flush();
		$request->session()->regenerate();
		return redirect('/admin');
	}
}
