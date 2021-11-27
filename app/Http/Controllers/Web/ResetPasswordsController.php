<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Model\Web\PasswordReset;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\ChangePassRequest;
use App\Http\Requests\Web\Auth\RedefinePassRequest;
use App\Model\Web\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ResetPasswordsController extends Controller
{

	use SendsPasswordResetEmails;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('web')->except('logout-web');
	}

	public function sendResetLinkEmail(Request $request)
	{
		if ($request->documento) {
			$documento = preg_replace("/[^0-9]/", '', $request->documento);

			$usuario = Usuario::find(preg_replace("/[^0-9]/", '', $documento));
			if (isset($usuario)) {
				if(!$usuario->conta_atualizada){
					$response = [
						'message' => 'O primeiro acesso não foi feito',
						'primeiro_acesso' => true,
						'errors' => [
							'documento' => [
								'Documento não fez o primeiro acesso.'
							]
						]
					];
					$statusCode = 422;
				}else{
					$pass_reset = PasswordReset::findByDocumento($documento);
					$data = [];
					$data['token'] = Str::slug(234) . date("YmdHis");
					if (isset($pass_reset)) {
						$pass_reset->edit($data);
					} else {
						$data['documento'] = $documento;
						$pass_reset = PasswordReset::store($data);
					}
					$usuario->sendResetPasswordNotification($pass_reset->token, $usuario->nome);
					$response = [
						'message' => 'E-mail enviado',
						'email' => $usuario->email,
					];
					$statusCode = 200;
				}

			} else {
				$response = [
					'message' => 'Documento inválido',
					'errors' => [
						'documento' => [
							'Documento inválido'
						]
					]
				];
				$statusCode = 422;
			}
		} else {
			$response = [
				'message' => 'Documento obrigatório',
				'errors' => [
					'documento' => [
						'Documento obrigatório'
					]
				]
			];
			$statusCode = 422;
		}
		return response()->json($response, $statusCode);
	}

	public function redefinePassword(RedefinePassRequest $request)
	{
		$data = $request->all();

		if (Auth::guard('api')->attempt(['documento' => Auth::user()->documento, 'password' => $data['password']])) {
			$usuario = Usuario::where('documento', Auth::user()->documento)->first();
			$usuario->password = $data['new_password'];
			if ($usuario->save())
				return response()->json([
					'message' => 'Senha alterada com sucesso'
				], 200);
			return response()->json([
				'message' => 'Não foi possível alterar a senha. Tente novamente mais tarde'
			], 422);
		}
		return response()->json([
			'message' => 'Autenticação inválida',
			'errors' => [
				'password' => [
					'Senha incorreta'
				]
			]
		], 422);
	}

	public function reset(Request $request)
	{
		$change_pass = new ChangePassRequest();
		$data = $request->all();
		$validate = $change_pass->validar($data);
		if (!$validate->fails()) {
			$pass_rest = PasswordReset::findByToken($data['token']);
			if (isset($pass_rest)) {
				$tempo_1 = strtotime($pass_rest->created_at);
				$tempo_1 = strtotime('+60 minutes', $tempo_1);
				if ($tempo_1 >= strtotime(Carbon::now()->toDateTimeString())) {
					$usuario = Usuario::find($pass_rest->documento);
					$usuario->password = $data['password'];
					$pass_rest->delete();
					$usuario->save();
					$response = [
						'message' => 'Senha alterada',
					];
					$statusCode = 200;
				} else {
					$pass_rest->delete();
					$response = [
						'message' => 'Token expirado',
						'errors' => [
							'token' => [
								'Este link de recuperação está expirado!'
							]
						]
					];
					$statusCode = 422;
				}
			} else {
				$response = [
					'message' => 'Token inválido',
					'errors' => [
						'token' => [
							'Este link de recuperação é inválido!'
						]
					]
				];
				$statusCode = 422;
			}
		} else {
			$statusCode = 422;
			$response = [
				'message' => 'Preencha todos os campos corretamente',
				'errors' => $validate->errors(),
			];
		}
		return response()->json($response, $statusCode);
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
