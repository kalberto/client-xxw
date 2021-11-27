<?php

namespace App\Http\Controllers\Auth;

use App\Model\Administrador;
use App\Model\PasswordReset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
	use SendsPasswordResetEmails;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

	public function showLinkRequestForm()
	{
		return view('admin.auth.reset');
	}

	public function sendResetLinkEmail(Request $request){
		if($request->email){
			$email = $request->email;
			$administrador = Administrador::findByEmail($email);
			if(isset($administrador)){
				$pass_reset = PasswordReset::findByEmail($email);
				$data = [];
				$data['token'] = str_random(234).date("YmdHis");
				if(isset($pass_reset)){
					$pass_reset->edit($data);
				}else{
					$data['email'] = $email;
					$pass_reset = PasswordReset::store($data);
				}
				$administrador->sendResetPasswordNotification($pass_reset->token, $administrador->nome);
				$response = [
					'msg' => 'E-mail enviado'
				];
				$statusCode = 200;
			}else{
				$response = [
					'msg' => 'E-mail inválido'
				];
				$statusCode = 401;
			}

		}else{
			$response = [
				'msg' => 'E-mail obrigatório'
			];
			$statusCode = 401;
		}
		return Response::json($response, $statusCode);
	}
}
