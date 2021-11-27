<?php

namespace App\Http\Controllers\Auth;

use App\Model\Administrador;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ChangePassRequest;
use App\Model\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

	use ResetsPasswords;

	/**
	 * Where to redirect users after resetting their password.
	 *
	 * @var string
	 */
	protected $redirectTo = '/home';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	public function showResetForm(Request $request, $token = null)
	{
		return view('admin.auth.token')->with(
			['token' => $token]
		);
	}

	public function reset(Request $request){
		$change_pass = new ChangePassRequest();
		$data = $request->all();
		$validate = $change_pass->validar($data);
		if(!$validate->fails()){
			$pass_rest = PasswordReset::findByToken($data['token']);
			if (isset($pass_rest)){
				$tempo_1 = strtotime($pass_rest->created_at);
				$tempo_1 = strtotime('+60 minutes',$tempo_1);
				if($tempo_1 >= strtotime(Carbon::now()->toDateTimeString())){
					$administrador = Administrador::findByEmail($pass_rest->email);
					$administrador->password = $data['password'];
					$pass_rest->delete();
					$administrador->save();
					$response = [
						'msg' => 'Senha alterada',
					];
					$statusCode = 200;
				}else{
					$pass_rest->delete();
					$response = [
						'msg' => 'Token expirado'
					];
					$statusCode = 401;
				}
			}
			else{
				$response = [
					'msg' => 'Token inválido'
				];
				$statusCode = 401;
			}
		}else{
			$statusCode = 422;
			$response = [
				'msg' => 'Preencha todos os campos corretamente',
				'error_validate' => $validate->errors(),
			];
		}
		return Response::json($response, $statusCode);
	}

	/**
	 * Get the password reset validation rules.
	 *
	 * @return array
	 */
	protected function rules()
	{
		return [
			'token' => 'required',
			'password' => 'required|confirmed|min:6',
			're_password' => 'required|same:password'
		];
	}
}
