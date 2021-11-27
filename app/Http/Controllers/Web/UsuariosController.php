<?php

namespace App\Http\Controllers\Web;

use App\Helpers\MonthHelper;
use App\Helpers\VariableHelper;
use App\Http\Requests\Web\Request;
use App\Mail\PreCadastroMail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Web\Usuario\LoginRequest;
use App\Http\Requests\Web\Usuario\EditRequest;
use App\Http\Requests\Web\Usuario\LoadRequest;
use App\Http\Requests\Web\Usuario\PerfilRequest;
use App\Http\Requests\Web\Usuario\LogoutRequest;
use App\Http\Requests\Web\Usuario\PreCadastroRequest;
use App\Model\Web\Usuario;
use App\Model\Conteudo;
use Illuminate\Support\Facades\Mail;

class UsuariosController extends Controller
{
	use AuthenticatesUsers {
		login as parentLogin;
	}

	public function username()
    {
        return 'documento';
    }

	protected function guard()
    {
        return Auth::guard('web');
    }

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('web')->except('logout-web');
	}

	public function updatePerfil(PerfilRequest $request) {
		$inputs = $request->all(['contato_responsavel','data_nascimento','email','celular']);
		$should_save = false;
		$usuario = Usuario::find(Auth::user()->documento);
		if(isset($inputs['contato_responsavel']) && $usuario->contato_responsavel != $inputs['contato_responsavel']){
			$usuario->contato_responsavel = $inputs['contato_responsavel'];
			$should_save = true;
		}
		if(isset($inputs['data_nascimento']) && $usuario->data_nascimento != $inputs['data_nascimento']){
			$usuario->data_nascimento = $inputs['data_nascimento'];
			$should_save = true;
		}
		if(isset($inputs['email']) && $usuario->email != $inputs['email']){
			$usuario->email = $inputs['email'];
			$should_save = true;
		}
		if(isset($inputs['celular']) && $usuario->telefone != $inputs['celular']){
			$usuario->telefone = $inputs['celular'];
			$should_save = true;
		}
		if($should_save)
			$usuario->save();
		return response()->json(['message' => "Atualizado com sucesso"],200);
	}

	public function update(EditRequest $request) {
		$data = $request->all();
		Usuario::atualizar($data);
		$statusCode = 200;
		$response = [
			'message' => 'Registro atualizado com sucesso'
		];
		return response()->json($response, $statusCode);
	}

	public function login(LoginRequest $request) {
        $this->validateLogin($request);
        if(method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
			$seconds = $this->limiter()->availableIn(
				$this->throttleKey($request)
			);
			return response()->json(['msg' => 'Dados inválidos.', 'errors' => ['tentativas' => ['Muitas tentativas de login. Tente novamente em '.$seconds.' segundos']]], 429);
        }
        if($this->attemptLogin($request)) {
            $user = Auth::guard('web')->user();
			Usuario::login($user->documento,$request);
	        $registro = $this->getUserLoginData($user);
			return response()->json($registro, 200);
        }
        $this->incrementLoginAttempts($request);
	    $data_login = $request->all();
	    if($data_login['documento'] == $data_login['password'] && DB::table('usuarios')->where([['documento','=',$data_login['documento']],['conta_atualizada','=',1]])->count() > 0){
	    	return response()->json(['msg' => 'Sua conta já foi atualizada.','errors' =>['documento' => ['Você já fez o primeiro acesso.']],'conta_atualizada' => true],422);
	    }
        return response()->json(['msg' => 'Dados inválidos.', 'errors' => ['documento' => ['CNPJ e/ou senha incorreto(s).']]], 422);
    }

	public function loginAdmin(Request $request) {
		$data = $request->all();
		if(isset($data['login_token'])){
			$usuario = Usuario::where('login_token','=',$data['login_token'])->first();
			if(isset($usuario)){
				$usuario->login_token = null;
				$usuario->save();
				Auth::guard('web')->login($usuario);
				$user = Auth::guard('web')->user();
				$registro = $this->getUserLoginData($user);
				return response()->json($registro, 200);
			}else{
				return response()->json(['msg' => 'Dados inválidos.', 'errors' => ['documento' => ['CNPJ e/ou senha incorreto(s).'],'token_expirado' => true]], 422);
			}
		}else{
			return response()->json(['msg' => 'Dados inválidos.', 'errors' => ['documento' => ['CNPJ e/ou senha incorreto(s).'],'sem_token' => true]], 422);
		}
	}

	protected function getUserLoginData($user) {
		$token = $user->createToken('user_auth_token')->accessToken;
		$registro = [
			'documento' => $user->documento,
			'email' => $user->email,
			'token' => $token,
			'nome_fantasia' => $user->nome_fantasia,
			'nivel' => $user->nivel,
			'conta_atualizada' => $user->conta_atualizada,
			'is_distribuidor' => $user->is_distribuidor,
			'selo' => $user->getSelo()
		];
		if($user->show_upgrade){
			$registro['show_upgrade'] = true;
			$registro['upgrade_nivel'] = $user->upgrade_nivel;
			$registro['old_selo'] = $user->getOldSelo();
			$user->removeShowUpgrade();
		}else
			$registro['show_upgrade'] = false;
		return $registro;
	}

	public function participar($slug) {
		$registro = Conteudo::where('slug',$slug)->where('deleted_at',null)->first();
		if($registro != null){
			$contudo_id = $registro['id'];
			$user = Auth::guard('web')->user();
			$documento = $user->documento;
		}else{
			return response()->json(['msg' => 'Registro não encontrado.'], 422);
		}
	}

	public function is_authenticated() {
    	if(Auth::guard('api')->check()){
			$user = Auth::guard('api')->user();
			$response = [
				'message' => 'Authenticated',
				'email' => $user->email
			];
		    if($user->show_upgrade){
			    $response['show_upgrade'] = true;
			    $response['upgrade_nivel'] = $user->upgrade_nivel;
			    $response['selo'] = $user->getSelo();
			    $response['old_selo'] = $user->getOldSelo();
			    $user->removeShowUpgrade();
		    }else
			    $response['show_upgrade'] = false;
		    return response()->json($response, 200);
	    }
    	return response()->json(['message'=> "Unauthenticated."],401);
    }

    public function perfilInterno() {
		$usuario = Auth::user();
		if(isset($usuario)){
			$nome_cidade = '';
			$nome_estado = '';
			$cidade = DB::table("cidades")->select('nome','estado_id')->where('id','=',$usuario->cidade_id)->first();
			if(isset($cidade)){
				$nome_cidade = $cidade->nome;
				$estado = DB::table("estados")->select('nome')->where('id','=',$cidade->estado_id)->first();
				if(isset($estado))
					$nome_estado = $estado->nome;
			}
			$retorno = [
				'documento' => $usuario->documento,
				'razao_social' => $usuario->razao_social,
				'nome_fantasia' => $usuario->nome_fantasia,
				'cidade' => $nome_cidade,
				'estado' => $nome_estado,
				'contato_responsavel' => $usuario->contato_responsavel,
				'data_nascimento' => date("d/m/Y", strtotime($usuario->data_nascimento)),
				'email' => $usuario->email,
				'celular' => $usuario->telefone
			];
			return response()->json(['registro'=> $retorno],200);
		}else{
			return response()->json(['message'=> "Unauthenticated."],401);
		}
    }

	public function load(LoadRequest $request) {
		$response = $request->user()
			->with('cidade.estado')
			->where('documento', Auth::user()->documento)->get()
			->makeHidden(['created_at', 'updated_at', 'deleted_at'])
			->first();
		return response()->json($response, 200);
	}

    public function logout(LogoutRequest $request) {
		$request->user()->token()->revoke();
		return response()->json(['message' => 'Deslogado com sucesso'], 200);
	}

	public function preCadastro(PreCadastroRequest $request) {
    	$data = $request->all();
		$cidade = DB::table("cidades")->select('nome','estado_id')->where('id','=',$data['cidade_id'])->first();
		$estado = DB::table("estados")->select('nome')->where('id','=',$data['estado_id'])->first();
    	$mail_data = [
    		'documento' => $data['documento'],
		    'razao_social' => $data['razao_social'],
		    'contato_responsavel' => $data['contato_responsavel'],
		    'telefone' => $data['telefone'],
		    'email' => $data['email'],
		    'cidade' => $cidade->nome,
		    'estado' => $estado->nome,
	    ];
		Mail::to(['email' => 'falecomselect@xxw.com'])->send(new PreCadastroMail($mail_data));
		return response()->json(['messagem'=> 'Recebemos seu cadastro e iremos analisar. Logo nosso time irá entrar em contato com você'],200);
	}

    public function estados() {
		$registros = DB::table('estados')->select('id', 'nome', 'uf')->get()->toArray();
		return response()->json($registros, 200);
	}

	public function cidades($estado_id) {
		$registros = DB::table('cidades')->select('id', 'nome')->where('estado_id', '=', $estado_id)->get()->toArray();
		return response()->json($registros, 200);
	}

	public function saldoVpc() {
		$saldos = Auth::user()->saldo_vpc;
		$registros = [];
		foreach ($saldos as $saldo){
			$validade = $saldo->data_validade;
			VariableHelper::convertDateFormat($validade,'Y-m-d','d/m/Y');
			$registros[] = [
				'mes' => MonthHelper::getMonth($saldo->mes),
				'saldo_total' => number_format($saldo->saldo_vpc,2,",","."),
				'saldo_provisionado' => number_format($saldo->saldo_provisionado,2,",","."),
				'saldo_disponivel' => number_format($saldo->saldo_disponivel,2,",","."),
				'validade' => $validade,
			];
		}
		$response = [
			'registros' => $registros
		];
		return response()->json($response);
	}
}
