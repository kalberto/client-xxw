<?php

namespace App\Http\Controllers\Admin;

use App\Model\Media;
use App\Model\LogAdministrador;
use App\Helpers\PaginatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use App\Http\Requests\Admin\Media\CreateRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Media\EditRequest;

class AssetsOldController extends Controller
{

	protected $searchable = ['file','thumbnail'];
	protected $page = 'assets';
	protected $editRoute = 'admin.assets.editar';

	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->role = 4;
		$this->model = new Media();
		$this->middleware('role:'.$this->role);
		$this->customFilter = false;
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
		return view('admin.assets.assets',$this->data);
	}

	public function loadAll(Request $request){
		$params = $request->all();
		$fields = '';
		if(isset($params['fields'])){
			$fields = $params['fields'];
		}
		$data = Media::with('media_root')->where('media_root_id','!=',2);
		$data = $data->orderBy('created_at')->get()->makeVisible(['id','created_at']);
		if($fields != ''){
			$fields = explode(',',$fields);
			$data->transform(function ($item,$key) use ($fields) {
				$media = collect($item)->only($fields);
				return $media;
			});
		}
		if($data->count() > 0){
			$statusCode = 200;
			$response = [
				'registros' => $data,
				'msg' => $data->count().' registros encontrados'
			];
		}else{
			$statusCode = 200;
			$response = [
				'registros' => [],
				'msg' => 'Nenhum registro encontrado'
			];
		}
		return Response::json($response, $statusCode);
	}

	public function loadMediaRoot(Request $request,$slug){
		//state - ativo ou não
		//fields - quais fields é para trazer
		//sort - order by
		//limit - quantos registros
		//q - busca na tabela
		$params = $request->all();
		//$limit = 10;
		$asc = true;
		$order_by = 'id';
		$state = true;
		$fields = '';
		$q = '';
		$sort = null;
		//if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0){
		//	$limit = $params['limit'];
		//}
		if(isset($params['sort'])){
			$order_by = $params['sort'];
			if(substr($order_by,0,1) == '-'){
				$asc = false;
				$order_by = substr($order_by,1);
			}
		}
		if(isset($params['state']) ){
			$state = boolval($params['state']);
		}
		if(isset($params['fields'])){
			$fields = $params['fields'];
		}
		if(Schema::hasColumn($this->model->getTable(),'ativo'))
			$data = Media::with(['media_root.media_resizes'])->join('media_root','media.media_root_id','=','media_root.id')->where(['ativo' => $state,'media_root.alias' => $slug]);
		else
			$data = Media::with(['media_root.media_resizes'])->select('media.*')->join('media_root','media.media_root_id','=','media_root.id')->where(['media_root.alias' => $slug]);
		if(isset($params['q'])){
			$q = $params['q'];
			if(sizeof($this->searchable) >= 1)
				$data = $data->where($this->searchable[0],'like','%'.$q.'%');
			for ($iterator = 1; $iterator <= sizeof($this->searchable) - 1; $iterator++){
				$data->orWhere($this->searchable[$iterator],'like','%'.$q.'%');
			}
		}
		if (Schema::hasColumn($this->model->getTable(), $order_by)){
			if($asc)
				$data = $data->orderBy($order_by)->get()->makeVisible(['id','created_at']);
			else
				$data = $data->orderByDesc($order_by)->get()->makeVisible(['id','created_at']);
		}
		if($fields != ''){
			$fields = explode(',',$fields);
			$data->transform(function ($item,$key) use ($fields) {
				return collect($item)->only($fields);
			});
		}
		if($data->count() > 0){
			foreach($data as $item){
				$item->link = route($this->editRoute,$item->id);
			}

			$statusCode = 200;
			$response = [
				//'pagination' => PaginatorHelper::paginate($data->lastPage(),$data->currentPage()),
				'registros' => $data,
				'msg' => $data->count().' registros encontrados'.($q!='' ? ' para '.$q.'!' : '!')
			];
		}else{
			$statusCode = 200;
			$response = [
				'pagination' => false,
				'registros' => [],
				'msg' => 'Nenhum registro encontrado'.($q!='' ? ' para '.$q.'!' : '!')
			];
		}

		return Response::json($response, $statusCode);
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
		return view('admin.assets.assets-create',$this->data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Support\Facades\Response
	 */
	public function store(Request $request)
	{
		ini_set('max_execution_time', 120);
		set_time_limit(120);
		if(!$this->hasPermission($this->role,2))
			return response()->json(['error' => 'Unauthorized.'], 403);
		$data = $request->all();
		$createRequest = new CreateRequest();
		$validate = $createRequest->validar($data);
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
		return view('admin.assets.assets-edit',$this->data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Request $response
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request,$id)
	{
		//
	}

	public function loadResource(Request $request, $id){
		if(!$this->hasPermission($this->role,2))
			return response()->json(['error' => 'Unauthorized.'], 403);
		if(isset($id) && is_numeric($id)){
			$noticia = $this->model::getMediaWithRoot($id);
			if($noticia != null){
				$response = [
					'media' => $noticia,
				];
				$statusCode = 200;
			}else{
				$statusCode = 404;
				$response = [
					'msg' => 'Media não encontrada.'
				];
			}
		}else{
			$statusCode = 404;
			$response = [
				'msg' => 'Media não encontrada.'
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
		$createRequest = new EditRequest();
		$validate = $createRequest->validar($data);
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
		if(isset($id) && is_numeric($id) && ($media = $this->model::find($id)) != null){
			if($this->model->delete_registro($id,$request->ip())){
				$statusCode = 200;
				$response = [
					'msg' => 'Registro deletado com sucesso.'
				];
			}else{
				$statusCode = 500;
				$response = [
					'msg' => 'Erro ao deletar o registro.'
				];
			}
		}
		else{
			$statusCode = 404;
			$response = [
				'msg' => 'Não foi possível encontrar esse registro.'
			];
		}
		return Response::json($response, $statusCode);
	}
}
