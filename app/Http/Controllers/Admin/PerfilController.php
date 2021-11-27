<?php

namespace App\Http\Controllers\Admin;

use App\Model\Perfil;
use App\Model\ModAdmPermissao;
use App\Model\LogAdministrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Perfil\PerfilRequest;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
	protected $searchable = ['nome'];
	protected $page = 'perfis';
	protected $editRoute = 'admin.perfis.editar';

	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->customFilter = false;
		$this->role = 3;
		$this->model = new Perfil();
		$this->middleware('role:'.$this->role);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$this->beforeAction();
		if(!$this->hasPermission($this->role,1))
			return view('errors.403',$this->data);
		return view('admin.perfis.perfis',$this->data);
	}

	public function all(){
		$registros = DB::table('perfis')->select('id','nome')->orderBy('nome')->get();
		return response()->json(['registros' => $registros],200);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$this->beforeAction();
		if(!$this->hasPermission($this->role,2))
			return view('errors.403',$this->data);
		return view('admin.perfis.perfis-create',$this->data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(!$this->hasPermission($this->role,2))
			return response()->json(['error' => 'Unauthorized.'], 403);
		$data = $request->all();
		$editRequest = new PerfilRequest();
		$validate = $editRequest->validar($data);
		if(!$validate->fails()){
			$saved = $this->model->store($data, $request->ip());
			if(isset($saved->saved) && $saved->saved){
				$statusCode = 200;
				$response = [
					'msg' => 'Registro salvo com sucesso',
					'url' => isset($saved->url) ? $saved->url : '/admin/'.$this->page,
				];
			}else{
				$statusCode = 503;
				$response = [
					'msg' => 'Service Unavailable',
					'error' => 'Ocorreu um erro ao salvar o registro',
				];
				if(isset($saved->error))
					$response['error'] = $saved->error;
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
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$this->beforeAction();
		if(!$this->hasPermission($this->role,2))
			return view('errors.403',$this->data);
		$this->data['id'] = $id;
		return view('admin.perfis.perfis-edit',$this->data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	public function loadResource(Request $response, $id){
		if(!$this->hasPermission($this->role,2))
			return response()->json(['error' => 'Unauthorized.'], 403);
		if(isset($id) && is_numeric($id)){
			$perfil = $this->model::getWithModPerm($id);
			if($perfil != null){
				$modulos = ModAdmPermissao::getAll();
				$response = [
					'registro' => $perfil,
					'modulos' => $modulos
				];
				$statusCode = 200;
			}else{
				$statusCode = 404;
				$response = [
					'msg' => 'Perfil não encontrado.'
				];
			}
		}else{
			$statusCode = 404;
			$response = [
				'msg' => 'Perfil não encontrado.'
			];
		}
		return Response::Json($response, $statusCode);
	}

	public function mod_adm_permissao(Request $response){
		if(!$this->hasPermission($this->role,2))
			return response()->json(['error' => 'Unauthorized.'], 403);
		$modulos = ModAdmPermissao::getAll();
		if($modulos->count() > 0){
			$response = [
				'modulos' => $modulos
			];
			$statusCode = 200;
		}else{
			$statusCode = 404;
			$response = [
				'msg' => 'Nenhuma permissão encontrada.'
			];
		}
		return Response::Json($response, $statusCode);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(!$this->hasPermission($this->role,2))
			return response()->json(['error' => 'Unauthorized.'], 403);
		$data = $request->all();
		$editRequest = new PerfilRequest();
		$validate = $editRequest->validar($data);
		if(!$validate->fails()){
			$saved = $this->model->edit($id,$data, $request->ip());
			if(isset($saved->saved) && $saved->saved){
				$statusCode = 200;
				$response = [
					'msg' => 'Registro salvo com sucesso',
					'url' => isset($saved->url) ? $saved->url : '/admin/'.$this->page,
				];
			}else{
				$statusCode = 503;
				$response = [
					'msg' => 'Service Unavailable',
					'error' => 'Ocorreu um erro ao salvar o registro',
				];
				if(isset($saved->error))
					$response['error'] = $saved->error;
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
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $id)
	{
		if(!$this->hasPermission($this->role,3))
			return response()->json(['error' => 'Unauthorized.'], 403);
		if($id == Auth::guard('admin')->user()->perfil()->first()->id) {
			$statusCode = 422;
			$response   = [
				'msg' => 'Não é possível deletar o perfil que está logado.'
			];
		}else{
			if(isset($id) && is_numeric($id) && ($perfil = Perfil::find($id)) != null){
				$data  = array (
					'administrador_id' => Auth::guard('admin')->user()->id,
					'registro_id' => $perfil->id,
					'tabela' => 'perfis',
					'tipo' => 'delete',
					'ip' => $request->ip()
				);
				LogAdministrador::store($data);
				if($perfil->delete()){
					$statusCode = 200;
					$response = [
						'msg' => 'Perfil deletado com sucesso!'
					];
				}else{
					$statusCode = 500;
					$response = [
						'msg' => 'Erro ao deletar o perfil!'
					];
				}
			}
			else{
				$statusCode = 404;
				$response = [
					'msg' => 'Não foi possível encontrar esse perfil.'
				];
			}
		}
		return Response::json($response, $statusCode);
	}
}
