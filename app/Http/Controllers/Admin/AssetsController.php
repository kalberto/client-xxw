<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\Asset\CreateRequest;
use App\Http\Requests\Admin\Asset\DeleteRequest;
use App\Http\Requests\Admin\Asset\EditRequest;
use App\Http\Requests\Admin\Asset\IdRequest;
use App\Http\Controllers\Controller;
use App\Model\Media;

class AssetsController extends Controller
{
    protected $searchable = ['file','thumbnail'];
	protected $page = 'assets';
	protected $editRoute = 'admin.assets.editar';

    public function __construct()
	{
		$this->role = 4;
		$this->model = new Media();
		$this->middleware('role:'.$this->role);
		$this->customFilter = true;
	}

    public function index()
	{
		$this->beforeAction();
		if(!$this->hasPermission($this->role,1))
			return view('errors.403',$this->data);
		return view('admin.assets.assets',$this->data);
	}

    public function create()
	{
		$this->beforeAction();
		if(!$this->hasPermission($this->role,2))
			return view('errors.403',$this->data);
		return view('admin.assets.assets-create',$this->data);
	}

    public function store(CreateRequest $request)
    {
        $data = $request->all();
		if($data['tipo'] == 2 && $data['video_is_link'] == 'true'){
			if($this->model->storeVideoLink($data, $request->ip())){
				$statusCode = 200;
				$response = [
					'msg' => 'Registro salvo com sucesso',
					'url' => '/admin/assets'
				];
			}else{
				$statusCode = 500;
				$response = [
					'msg' => 'Ocorreu um erro ao salvar'
				];
			}
			return response()->json($response,$statusCode);
		}else {
			if($this->model->store($data, $request->ip())){
				$statusCode = 200;
				$response = [
					'msg' => 'Registro salvo com sucesso',
					'url' => '/admin/assets'
				];
			}else{
				$statusCode = 500;
				$response = [
					'msg' => 'Ocorreu um erro ao salvar'
				];
			}
			return response()->json($response,$statusCode);
		}
    }

	public function show(IdRequest $request, $id)
    {
        $registro = Media::with(['conteudo','media_root'])->find($id);
		return response()->json(['registro' => $registro],200);
    }

	public function edit($id) {
		$this->beforeAction();
		$this->data['id'] = $id;
		if (!$this->hasPermission($this->role, 2)) {
			return view('errors.403', $this->data);
		}
		return view('admin.assets.assets-edit', $this->data);
	}

	public function update(EditRequest $request, $id)
    {
        $data = $request->all();
		if($this->model->edit($id, $data, $request->ip())) {
			$statusCode = 200;
			$response = [
				'msg' => 'Registro editado com sucesso.',
				'url' => '/admin/assets'
			];
		}else {
			$statusCode = 500;
			$response = [
				'msg' => 'Ocorreu um erro ao editar o registro.'
			];
		}
		return response()->json($response,$statusCode);
    }

	public function destroy(DeleteRequest $request,$id){
		$this->model->delete_registro($id,$request->ip());
		return response()->json(['msg' => 'Registro deletado com sucesso!'],200);
	}

	protected function customWhere($p_q, &$data){	
        if(isset($p_q['q'])){
            $data->orWhereHas('conteudo', function($query) use ($p_q){
                $query->where('nome','like','%'.$p_q['q'].'%');
            });
        }
    }
}
