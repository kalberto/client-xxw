<?php

namespace App\Http\Controllers\Admin;

use App\Model\Configuracao;
use App\Http\Requests\Admin\Configuracao\ConfiguracaoRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ConfiguracaoController extends Controller
{

	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->role = 9;
		$this->model = new Configuracao();
		$this->middleware('role:'.$this->role);
	}

	public function edit()
	{
		$this->beforeAction();
		if(!$this->hasPermission($this->role,2))
			return view('errors.403',$this->data);
		return view('admin.configuracoes.configuracao',$this->data);
	}

	/**
	 * Display the specified resource.
	 * RF00
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
		if(!$this->hasPermission($this->role,2))
			return Response::json(['error' => 'Unauthorized.'], 403);
		$statusCode = 404;
		$response = [
			'msg' => 'Não foi possível encontrar as configurações'
		];
		$configuracao = Configuracao::with('complementos')->first();
		if(isset($configuracao)){
			$statusCode = 200;
			$response = $configuracao;
		}
		return Response::json($response,$statusCode);
	}

	/**
	 * Update the specified resource in storage.
	 * RF0030
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		if(!$this->hasPermission($this->role,2))
			return Response::json(['error' => 'Unauthorized.'], 403);
		if(($configuracao = Configuracao::first()) != null){
			$updateRequest = new ConfiguracaoRequest();
			$data = $request->all();
			$validate = $updateRequest->validar($data);
			if(!$validate->fails()){
				if($configuracao->edit($data,$request->ip())){
					$statusCode = 200;
					$response = [
						'msg' => 'Configurações editadas com sucesso!',
						'url' => '/admin'
					];
				}else{
					$statusCode = 500;
					$response = [
						'msg' => 'Ocorreu um erro ao editar o Administrador'
					];
				}
			}else{
				$statusCode = 422;
				$response = [
					'msg' => 'Preencha os campos corretamente',
					'error_validate' => $validate->errors(),
				];
			}
		}else{
			$statusCode = 404;
			$response = [
				'msg' => 'Não foi possível encontrar as configurações'
			];
		}
		return Response::json($response, $statusCode);
	}
}
