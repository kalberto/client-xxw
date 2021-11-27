<?php

namespace App\Http\Controllers\Admin;

use App\Model\Perfil;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Administradores\CreateAdministradorRequest;
use App\Http\Requests\Admin\Administradores\EditAdministradorRequest;
use App\Http\Requests\Admin\Administradores\SenhaAdministradorRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Model\Administrador;
use App\Model\ModuloAdministrador;
use App\Model\LogAdministrador;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class AdministradorController extends Controller
{
	protected $searchable = ['nome', 'sobrenome', 'email'];
	protected $page = 'administradores';
	protected $editRoute = 'admin.administradores.editar';
	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->customFilter = false;
		$this->role = 2;
		$this->model = new Administrador();
		$this->middleware('role:'.$this->role,['except' => ['show','loadAdministrador','update']]);
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
		return view('admin.administradores.administradores',$this->data);
	}

	public function loadAdministradores()
	{
		if(!$this->hasPermission($this->role,2))
			return response()->json(['error' => 'Unauthorized.'], 403);
		$admin = Administrador::all();
		foreach($admin as $administradores){
			$administradores->link = route('admin.administradores.editar',$administradores->id);
			$array[] = $administradores;
		}
		$response = $array;
		return Response::json($response);
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
		return view('admin.administradores.administradores-create', $this->data);
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
		$this->beforeAction();
		$data = $request->all();
		$data['api_token'] = Str::random(46).date("YmdHis");
		$createRequest = new CreateAdministradorRequest();
		$validate = $createRequest->validar($data);
		if(!$validate->fails()){
			if(Administrador::store($data, $request->ip())){
				$statusCode = 200;
				$response = [
					'msg' => 'Administrador cadastrado com sucesso!',
					'url' => '/admin/administradores'
				];
			}else{
				$statusCode = 500;
				$response = [
					'msg' => 'Ocorreu um erro ao cadastrar o Administrador.'
				];
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
		if(!$this->hasPermission($this->role,2) && Auth::guard('admin')->user()->id != $id)
			return view('errors.403',$this->data);
		$this->data['id'] = $id;
		return view('admin.administradores.administradores-edit',$this->data);
	}

	public function loadAdministrador(Request $response, $id)
	{
		$has_permission = $this->hasPermission($this->role,2);
		if(!$has_permission && Auth::guard('admin')->user()->id != $id)
			return response()->json(['error' => 'Unauthorized.'], 403);
		if(isset($id) && is_numeric($id)){
			$admin = Administrador::getAdministrador($id);
			if($admin != null){
				if($has_permission)
					$perfis = Perfil::getAll();
				else
					$perfis = Perfil::getLoggedPerfil();
				$response = [
					'administrador' => $admin,
					'perfis' => $perfis
				];
				$statusCode = 200;
			}else{
				$statusCode = 404;
				$response = [
					'msg' => 'Administrador não encontrado.'
				];
			}
		}else{
			$statusCode = 404;
			$response = [
				'msg' => 'Administrador não encontrado.'
			];
		}
		return Response::json($response, $statusCode);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id){
		//
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
		$this->beforeAction();
		$has_permission = $this->hasPermission($this->role,2);
		if(!$has_permission && Auth::guard('admin')->user()->id != $id)
			return response()->json(['error' => 'Unauthorized.'], 403);
		try{
			if(isset($id) && is_numeric($id) && Administrador::find($id) != null){
				$updateRequest = new EditAdministradorRequest();
				$data = $request->all();
				$validate = $updateRequest->validar($data);
				if(!$validate->fails()){
					if(!$has_permission){
						$data['perfil_id'] = Auth::guard('admin')->user()->perfil_id;
						$url = '/admin/administradores/editar/'.$id;
					}else
						$url = '/admin/administradores';
					if(Administrador::edit($id,$data, $request->ip())){
						$statusCode = 200;
						$response = [
							'msg' => 'Registro editado com sucesso!',
							'url' => $url
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
						'msg' => 'Ocorreu um erro ao editar o Administrador',
						'error_validate' => $validate->errors(),
					];
				}
			}else{
				$statusCode = 404;
				$response = [
					'msg' => 'Não foi possível encontrar esse Administrador.'
				];
			}
		}catch (Exception $e){
			$statusCode = 503;
			$response = [
				"error" => "Service Unavailable"
			];
		}
		return Response::json($response, $statusCode);
	}

	public function senha(Request $request, $id)
	{
		if(!$this->hasPermission($this->role,2) && Auth::guard('admin')->user()->id != $id)
			return response()->json(['error' => 'Unauthorized.'], 403);
		try{
			$data = $request->all();
			$senhaRequest = new SenhaAdministradorRequest();
			$validate = $senhaRequest->validar($data);
			if(!$validate->fails()){
				$usuario = Auth::guard('admin')->user();
				$login =[
					'email' => $usuario->email,
					'password' => $data['password']
				];
				if(Auth::guard('admin')->once($login)){
					$administrador = Administrador::find($id);
					if(isset($administrador)){
						if($administrador->editPass($data, $request->ip())){
							$statusCode = 200;
							$response = [
								'msg' => 'Dados de acesso do administrador editados com sucesso!',
								'url' => '/admin/administradores'
							];
						}else{
							$statusCode = 500;
							$response = [
								'msg' => 'Ocorreu um erro ao editar o Administrador'
							];
						}
					}else{
						$statusCode = 404;
						$response = [
							'msg' => 'Não foi possível encontrar esse Administrador.'
						];
					}
				}else{
					$statusCode = 422;
					$response = [
						'msg' => 'Senha atual está incorreta.',
						'url' => '/admin/administradores',
						'error_validate' => ["password" => "Senha usuário logado está incorreta."],
					];
				}
			}else{
				$statusCode = 422;
				$response = [
					'msg' => 'Ocorreu um erro ao editar o Administrador',
					'error_validate' => $validate->errors(),
				];

			}
		}catch (Exception $e){
			$statusCode = 503;
			$response = [
				"error" => "Service Unavailable"
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
	public function destroy(Request $request, $id = null)
	{
		if(!$this->hasPermission($this->role,3))
			return response()->json(['error' => 'Unauthorized.'], 403);
		if($id == Auth::guard('admin')->user()->id){
			$statusCode = 422;
			$response = [
				'msg' => 'Não é possível deletar o administrador que está logado.'
			];
		}else{
			if(isset($id) && is_numeric($id) && ($administrador = Administrador::find($id)) != null){
				$data  = array (
					'administrador_id' => Auth::guard('admin')->user()->id,
					'registro_id' => $administrador->id,
					'tabela' => 'administradores',
					'tipo' => 'delete',
					'ip' => $request->ip()
				);
				LogAdministrador::store($data);
				if($administrador->delete()){
					$statusCode = 200;
					$response = [
						'msg' => 'Administrador deletado com sucesso!'
					];
				}else{
					$statusCode = 500;
					$response = [
						'msg' => 'Erro ao deletar o Administrador!'
					];
				}
			}
			else{
				$statusCode = 404;
				$response = [
					'msg' => 'Não foi possível encontrar esse Administrador.'
				];
			}
		}
		return Response::json($response, $statusCode);
	}
}
