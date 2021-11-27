<?php

namespace App\Http\Controllers\Admin;


use App\Helpers\MediaHelper;
use App\Helpers\PaginatorHelper;
use App\Http\Requests\Admin\VPC\DeleteAnexoRequest;
use App\Http\Requests\Admin\VPC\EditRequest;
use App\Http\Requests\Admin\VPC\ShowRequest;
use App\Http\Requests\Admin\VPC\UploadAnexoRequest;
use App\Model\Canais\VPC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class VPCController extends Controller
{
	protected $editRoute = 'admin.vpc.editar';

	public function __construct()
	{
		$this->customFilter = false;
		$this->role = 12;
		$this->model = new VPC();
	}

	protected function guard()
	{
		return Auth::guard('admin');
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
		return view('admin.vpc.vpc',$this->data);
	}


	public function load(Request $request){
		$params = $request->all();
		$limit = 10;
		$status ='all';
		$fields = '';
		$q = '';
		$sort = null;
		if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0){
			$limit = $params['limit'];
		}
		if(isset($params['status']) ){
			$status = boolval($params['status']);
		}
		if(isset($params['fields'])){
			$fields = $params['fields'];
		}
			$data = VPC::where('assunto_vpc_id','!=',null);
		$data = $data->orderBy('updated_at')->paginate($limit);
		if (Schema::hasColumn($this->model->getTable(), 'created_at')){
			$data->setCollection($data->getCollection()->makeVisible(['id','created_at']));
		}else{
			$data->setCollection($data->getCollection()->makeVisible(['id']));
		}
		if($fields != ''){
			$fields = explode(',',$fields);
			$data->transform(function ($item,$key) use ($fields) {
				return collect($item)->only($fields);
			});
		}
		if($data->total() > 0){
			foreach ($data as $item){
				if($this->editRoute != ''){
					$item->link = route($this->editRoute,$item->getKey());
				}
			}
			$statusCode = 200;
			$response = [
				'pagination' => PaginatorHelper::paginate($data->lastPage(),$data->currentPage()),
				'registros' => $data->appends($params),
				'msg' => $data->total().' registro(s) encontrado(s)'.($q!='' ? ' para '.$q.'!' : '!')
			];
		}else{
			$statusCode = 200;
			$response = [
				'pagination' => false,
				'registros' => [],
				'msg' => 'Nenhum registro encontrado'.($q!='' ? ' para '.$q.'!' : '!')
			];
		}

		return response()->json($response, $statusCode);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param ShowRequest $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(ShowRequest $request, $id)
	{
		return response()->json(['registro' => VPC::show($id)], 200);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$this->beforeAction();
		if(!$this->hasPermission($this->role,2))
			return view('errors.403',$this->data);
		$this->data['id'] = $id;
		return view('admin.vpc.vpc-edit',$this->data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param EditRequest|Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(EditRequest $request, $id)
	{
		$data = $request->all();
		$data['id'] = $id;
		if(VPC::edit($data)){
			$statusCode = 200;
			$response = [
				'msg' => 'Registro salvo com sucesso'
			];
		}else{
			$statusCode = 422;
			$response = [
				'msg' => 'Essa VPC não pode ser alterada.'
			];
		}
		return response()->json($response, $statusCode);
	}

	public function uploadAnexo(UploadAnexoRequest $request,$id){
		$data = $request->all();
		$response = [
			'msg' => 'Arquivo adicionado com sucesso.'
		];
		$status_code = 200;
		$vpc = VPC::find($id);
		if(!$vpc->addAnexo($data)){
			$response = [
				'msg' => 'Não foi possivel adicionar o arquivo.'
			];
			$status_code = 422;
		}
		$response['anexos_admin'] = $vpc->anexos_admin;
		return response()->json($response,$status_code);
	}

	public function deleteAnexo(DeleteAnexoRequest $request, $id){
		$arquivo = DB::table('arquivos_admin_vpc')->find($id);
		MediaHelper::delete_file('upload/vpc/anexos/admin',$arquivo->file);
		unset($arquivo);
		DB::table('arquivos_admin_vpc')->delete($id);
		return response()->json(['msg' => 'Registro deletado com sucesso!']);
	}
}
