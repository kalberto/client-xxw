<?php

namespace App\Http\Controllers;

use App\Model\Configuracao;
use App\Helpers\VariableHelper;
use App\Helpers\PaginatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $role = 0;
	protected $data = [];
	protected $model = null;
	protected $searchable = ['nome'];
	protected $page = '';
	protected $editRoute = '';

	protected function beforeAction(){
		$configuracao = Configuracao::with('complementos')->first();
		$id_perfil = Auth::guard('admin')->user()->perfil()->first()->id;
		$modulos = DB::table('perfil_mod_adm_permissao')
		             ->select(['modulo_administrador.id as id','modulo_administrador.nome as nome','modulo_administrador.icone as icone', 'modulo_administrador.modulo_url as modulo_url','modulo_administrador.is_father as is_father'])
		             ->join('mod_adm_permissao','perfil_mod_adm_permissao.mod_adm_perm_id','=','mod_adm_permissao.id')
		             ->join('modulo_administrador','mod_adm_permissao.mod_adm_id','=','modulo_administrador.id')
		             ->where([['perfil_id','=',$id_perfil],['modulo_administrador.menu','=',1]])
		             ->orderBy('modulo_administrador.order')
		             ->groupBy('modulo_administrador.id')
					 ->get();
		foreach ($modulos as $key => $modulo){
			if($modulo->is_father){
			$modulos[$key]->children = DB::table('modulo_administrador')
											->select(['modulo_administrador.id as id','modulo_administrador.nome as nome','modulo_administrador.icone as icone', 'modulo_administrador.modulo_url as modulo_url'])
											->where('father','=',$modulo->id)
											->orderBy('modulo_administrador.order')->orderBy('modulo_administrador.id','asc')
											->get();
			}
		}
		$this->data['current_role'] = $this->role;
		$this->data['modulos'] = $modulos;
		$this->data['configuracao'] = $configuracao;
		$this->data['super_adm'] = Auth::guard('admin')->user()->super_user ? true : false;
	}

	protected function hasPermission($modulo,$permission){
		$id_perfil = Auth::guard('admin')->user()->perfil()->first()->id;
		$count = DB::table('perfil_mod_adm_permissao')
		           ->join('mod_adm_permissao','perfil_mod_adm_permissao.mod_adm_perm_id','=','mod_adm_permissao.id')
		           ->where([['perfil_id','=',$id_perfil],['mod_adm_permissao.mod_adm_id','=',$modulo],['mod_adm_permissao.permissao_id','=',$permission]])
		           ->count();
		if($count > 0)
			return true;
		else
			return false;
    }

    /**
	 * Load a listing of the resource.
	 *
	 * @param Request $request
	 * @return \Illuminate\Support\Facades\Response
	 */
	public function load(Request $request){
		//state - ativo ou não
		//fields - quais fields é para trazer
		//sort - order by
		//limit - quantos registros
		//q - busca na tabela
		$params = $request->all();
		$limit = 10;
		$asc = true;
		$order_by = 'id';
		$state ='both';
		$fields = '';
		$q = '';
		$sort = null;
		if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0){
			$limit = $params['limit'];
		}
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
		if($state !== 'both' && Schema::hasColumn($this->model->getTable(),'ativo'))
			$data = $this->model->get()->where(['ativo' => $state]);
		else
			$data = $this->model->get();
			if(isset($params['q'])){
				$q = $params['q'];
				if(sizeof($this->searchable) >= 1)
					if(strtolower($this->searchable[0]) == 'published_at' || strtolower($this->searchable[0]) == 'created_at'){
						$d = $q;
						VariableHelper::convertDateFormat($d,"d/m/Y","Y-m-d");
						if($d != false)
							$data = $data->where($this->searchable[0],'like','%'.$d.'%');
					}else{
						$data = $data->where($this->searchable[0],'like','%'.$q.'%');
					}
				for ($interator = 1; $interator <= sizeof($this->searchable) - 1; $interator++){
					if(strtolower($this->searchable[$interator]) == 'published_at' || strtolower($this->searchable[$interator]) == 'created_at'){
						$d = $q;
						VariableHelper::convertDateFormat($d,"d/m/Y","Y-m-d");
						if($d != false)
							$data->orWhere($this->searchable[$interator],'like','%'.$d.'%');
					}else{
						$data->orWhere($this->searchable[$interator],'like','%'.$q.'%');
					}
				}
			}
		if($this->customFilter){
			$this->customWhere($params,$data);
		}
		if (Schema::hasColumn($this->model->getTable(), $order_by)){
			if($asc)
				$data = $data->orderBy($order_by)->paginate($limit);
			else
				$data = $data->orderByDesc($order_by)->paginate($limit);
		}else{
			$data = $data->paginate($limit);
		}
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
			if($this->editRoute != ''){
				foreach($data as $item){
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

		return Response::json($response, $statusCode);
	}
}
