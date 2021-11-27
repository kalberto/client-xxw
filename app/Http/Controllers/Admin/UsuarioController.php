<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MediaHelper;
use App\Helpers\PaginatorHelper;
use App\Helpers\VariableHelper;
use App\Http\Requests\Admin\Usuario\CreateRequest;
use App\Http\Requests\Admin\Usuario\DeleteRequest;
use App\Http\Requests\Admin\Usuario\EditRequest;
use App\Http\Requests\Admin\Usuario\ShowRequest;
use App\Imports\ClassificacaoImport;
use App\Imports\SaldoVpcImport;
use App\Imports\UsuariosImport;
use App\Imports\NiveisImport;
use App\Imports\MetasImport;
use App\Model\Canais\Usuario;
use App\Model\LogAdministrador;
use App\Model\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Helpers\MonthHelper;

class UsuarioController extends Controller
{
	protected $searchable = ['nome_fantasia','razao_social','documento'];
	protected $editRoute = 'admin.usuarios.editar';

	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->customFilter = false;
		$this->role = 5;
		$this->model = new Usuario();
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
		return view('admin.usuarios.usuarios',$this->data);
	}


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
		$conta ='both';
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
		if(isset($params['conta']) ){
			$conta = boolval($params['conta']);
		}
		if(isset($params['fields'])){
			$fields = $params['fields'];
		}
		if($state !== 'both' && Schema::hasColumn($this->model->getTable(),'ativo'))
			$data = $this->model->get()->where(['ativo' => $state]);
		else
			$data = $this->model->get();
		if($conta !== 'both')
			$data = $data->where([['conta_atualizada','=',$conta]]);
		if(isset($params['q'])){
			$q = $params['q'];
			if(sizeof($this->searchable) >= 1)
				if(strtolower($this->searchable[0]) == 'documento' && preg_replace("/[^0-9]/", '',$q) !== '')
					$data = $data->where($this->searchable[0],'like','%'.preg_replace("/[^0-9]/", '',$q).'%');
				else
					$data = $data->where($this->searchable[0],'like','%'.$q.'%');
			for ($interator = 1; $interator <= sizeof($this->searchable) - 1; $interator++){
				if(strtolower($this->searchable[$interator]) == 'documento' && preg_replace("/[^0-9]/", '',$q) !== '')
					$data->orWhere($this->searchable[$interator],'like','%'.preg_replace("/[^0-9]/", '',$q).'%');
				else
					$data->orWhere($this->searchable[$interator],'like','%'.$q.'%');
			}
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
			foreach ($data as $item){
				if($this->editRoute != ''){
					$item->link = route($this->editRoute,$item->getKey());
				}
				$item->acesso_link = route('admin.usuarios.acessar',$item->getKey());
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
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$this->beforeAction();
		if(!$this->hasPermission($this->role,2))
			return view('errors.403',$this->data);
		return view('admin.usuarios.usuarios-create',$this->data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CreateRequest|Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(CreateRequest $request)
	{
		$data = $request->all();
		if(Usuario::store($data, $request->ip())){
			$statusCode = 200;
			$response = [
				'msg' => 'Registro salvo com sucesso',
				'url' => '/admin/usuarios'
			];
		}else{
			$statusCode = 500;
			$response = [
				'msg' => 'Ocorreu um erro ao salvar'
			];
		}

		return response()->json($response,$statusCode);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param ShowRequest $request
	 * @param  string $documento
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(ShowRequest $request, $documento)
	{
		return response()->json(['registro' => Usuario::show($documento)], 200);
	}

	public function perfis(){
		$registros = DB::table('perfis_usuarios')->select(['id','nome'])->get()->toArray();
		return response()->json(['registros' => $registros],200);
	}

	public function niveis($id){
		$registros = DB::table('niveis')->select(['id','nome'])->where('perfil_id','=',$id)->get()->toArray();
		return response()->json(['registros' => $registros],200);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string  $documento
	 * @return \Illuminate\Http\Response
	 */
	public function edit($documento)
	{
		$this->beforeAction();
		if(!$this->hasPermission($this->role,2))
			return view('errors.403',$this->data);
		$this->data['documento'] = $documento;
		return view('admin.usuarios.usuarios-edit',$this->data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param EditRequest|Request $request
	 * @param  string $documento
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(EditRequest $request, $documento)
	{
		$data = $request->all();
		$data['documento'] = $documento;
		if(Usuario::edit($data, $request->ip())){
			$statusCode = 200;
			$response = [
				'msg' => 'Registro salvo com sucesso'
			];
		}else{
			$statusCode = 500;
			$response = [
				'msg' => 'Ocorreu um erro ao salvar'
			];
		}
		return response()->json($response, $statusCode);
	}

	public function totalUsuarios(){
		if(!$this->hasPermission($this->role,1))
			return response()->json(['error' => 'Unauthorized.'], 403);
		$total = Usuario::where('ativo',1)->get();
		$response = [
			'total' => count($total)
		];
		$statusCode = 200;
		return response()->json($response, $statusCode);
	}

	public function all(){
		$registros = DB::table('niveis')->select(['id','nome'])->whereNull('deleted_at')->get();
		return response()->json(['registros' => $registros],200);
	}

	public function niveisById($id){
		$registros = DB::table('niveis')->select(['id','nome'])->where('perfil_id',$id)->whereNull('deleted_at')->get();
		return response()->json(['registros' => $registros],200);
	}

	public function niveisByPerfis($string_ids){
		$ids = explode(",", $string_ids);
		$registros = DB::table('niveis')->select(['id','nome'])->whereIn('perfil_id',$ids)->whereNull('deleted_at')->get();
		return response()->json(['registros' => $registros],200);
	}

	public function acessar($documento){
		$this->beforeAction();
		if(!$this->hasPermission($this->role,1))
			return view('errors.403',$this->data);
		$usuario = Usuario::find($documento);
		if(isset($usuario)){
			$usuario->login_token = Str::random(46).date("YmdHis");
			$usuario->save();
			return redirect('/login?login_token='.$usuario->login_token);
		}else{
			return view('errors.404',$this->data);
		}
	}

	public function upload(Request $request){
		$params = $request->all();
		if(isset($params['file'])){
			$file = $request->file('file');
			$path = 'upload/usuarios/tmp/';
			$file_name = MediaHelper::upload($file,$path,$file->getClientOriginalName());
			$heading = (new HeadingRowImport)->toArray($path.$file_name,'web_site');
			if(sizeof($heading) > 1){
				$statusCode = 422;
				$response = ['msg' => 'A planilha deve ter apenas uma página.'];
			}else{
				if($this->checkHeader($heading)){
					$errors = [];
					$error = false;
					$db_records = [];//DB::table('usuarios')->select(DB::raw('documento as unique_key'))->pluck('unique_key')->toArray();
					$excel_sheets = (new UsuariosImport())->toArray($path.$file_name,'web_site');
					foreach ($excel_sheets[0] as $key => $cell){
						if($cell['cnpj'] != '' && $cell['nome_fantasia'] != '' && $cell['razao_social'] != ''){
							$current_unique_key = preg_replace("/[^0-9]/", "",$cell['cnpj']);
							if(!in_array($current_unique_key,$db_records)){
								$db_records[] = $current_unique_key;
								if($cell['uf'] != '' && $cell['cidade'] !== ''){
									if(!($cell['uf'] == '-' && $cell['cidade'] == '-')){
										$cidade = DB::table('estados')->join('cidades','estados.id','=','cidades.estado_id')
										            ->where([['uf','=',mb_strtoupper($cell['uf'],"UTF-8")],['seo_slug','=',Str::slug($cell['cidade'])]])
										            ->pluck('cidades.id')->first();
										if(!isset($cidade)){
											$error = true;
											$errors[] = 'Linha '.($key + 2).' Cidade-UF não encontrada';
										}
									}
								}else{
									$error = true;
									$errors[] = 'Linha '.($key + 2).' Cidade e/ou UF não preenchido';
								}
								if($cell['perfil'] != '' && $cell['nivel'] !== ''){
									$nivel = DB::table('perfis_usuarios')->join('niveis','perfis_usuarios.id','=','niveis.perfil_id')
									           ->where([['perfis_usuarios.nome','like',$cell['perfil']],['niveis.nome','=',$cell['nivel']]])
									           ->pluck('niveis.id')->first();
									if(!isset($nivel)){
										$error = true;
										$errors[] = 'Linha '.($key + 2).' Perfil/Nível não encontrado';
									}
								}else{
									$error = true;
									$errors[] = 'Linha '.($key + 2).' Perfil e/ou Nível não preenchido';
								}
							}else{
								$error = true;
								$errors[] = 'Linha '.($key + 2).' registro repetido (banco ou planilha)';
							}
						}else{
							$error = true;
							$errors[] = 'Linha '.($key + 2).' campos obrigatorios vazios';
						}
					}
					if($error){
						$statusCode = 422;
						$response = ['msg' => 'Ocorreu um erro na importação', 'errors' => $errors];
					}else{
						$data_log = array(
							'administrador_id' => Auth::guard('admin')->id(),
							'registro_id' => null,
							'tabela' => 'usuarios',
							'tipo' => 'import_start',
							'ip' => $request->ip(),
							'alteracoes' => ''
						);
						LogAdministrador::store($data_log);
						Excel::import(new UsuariosImport(),$path.$file_name,'web_site');
						$statusCode = 200;
						$response = ['msg' => 'Cadastrado com sucesso'];
						$data_log = array(
							'administrador_id' => Auth::guard('admin')->id(),
							'registro_id' => null,
							'tabela' => 'usuarios',
							'tipo' => 'import_end',
							'ip' => $request->ip(),
							'alteracoes' => ''
						);
						LogAdministrador::store($data_log);
					}
				}else{
					$statusCode = 422;
					$response = ['msg' => 'Por favor, utilize a planilha base como referência.'];
				}
			}
			return response()->json($response,$statusCode);
		}
		return response()->json(['msg'=>'Arquivo não encontrado.'],422);
	}

	public function uploadMeta(Request $request){
		$params = $request->all();
		if(isset($params['file'])){
			$file = $request->file('file');
			$path = 'upload/usuarios/tmp/';
			$file_name = MediaHelper::upload($file,$path,$file->getClientOriginalName());
			$heading = (new HeadingRowImport)->toArray($path.$file_name,'web_site');
			if(sizeof($heading) > 1){
				$statusCode = 422;
				$response = ['msg' => 'A planilha deve ter apenas uma página.'];
			}else{
				if($this->checkHeaderMeta($heading)){
					$errors = [];
					$error = false;
					$excel_sheets = (new MetasImport())->toArray($path.$file_name,'web_site');
					foreach ($excel_sheets[0] as $key => $cell){
						if($cell['mes'] == '') {
							$error = true;
							$errors[] = 'Linha '.($key + 2).' Mes não preenchido';
						}
						if($cell['ano'] == '') {
							$error = true;
							$errors[] = 'Linha '.($key + 2).' Ano não preenchido';
						}
						if($cell['cnpj'] != '') {
							$cnpj = preg_replace("/[^0-9]/", "",$cell['cnpj']);
							if(strlen($cnpj) != 14){
								$error = true;
								$errors[] = 'Linha '.($key + 2).'O CNPJ '.$cell['cnpj'].' inválido';
							}
							if(DB::table('usuarios')->where('documento','=',$cnpj)->count() == 0){
								$error = true;
								$errors[] = 'Linha '.($key + 2).' O CNPJ '.$cell['cnpj'].' não cadastrado no banco de dados';
							}
						}else{
							$error = true;
							$errors[] = 'Linha '.($key + 2).'CNPJ não preenchido';
						}
					}
					$data_log = array(
						'administrador_id' => Auth::guard('admin')->id(),
						'registro_id' => null,
						'tabela' => 'metas_usuario',
						'tipo' => 'import_start',
						'ip' => $request->ip(),
						'alteracoes' => ''
					);
					LogAdministrador::store($data_log);
					Excel::import(new MetasImport(),$path.$file_name,'web_site');
					if($error){
						$statusCode = 422;
						$response = ['msg' => 'A importação aconteceu, mas teve os seguintes erros:', 'errors' => $errors];
					}else{
						$statusCode = 200;
						$response = ['msg' => 'Cadastrado com sucesso'];
					}
					$data_log = array(
						'administrador_id' => Auth::guard('admin')->id(),
						'registro_id' => null,
						'tabela' => 'usuarios',
						'tipo' => 'import_end',
						'ip' => $request->ip(),
						'alteracoes' => ''
					);
					LogAdministrador::store($data_log);
				}else{
					$statusCode = 422;
					$response = ['msg' => 'Por favor, o header da planilha deve ser CNPJ,ANO,MES,VALOR,VALOR_META,VALOR_META_TRIMESTRE,VALOR_FALTA_VPC,VALOR_FALTA_REBATE,REBATE_DISPONIVEL'];
				}
			}
			return response()->json($response,$statusCode);
		}
		return response()->json(['msg'=>'Arquivo não encontrado.'],422);
	}

	public function uploadClassificacao(Request $request){
		$params = $request->all();
		if(isset($params['file'])){
			$file = $request->file('file');
			$path = 'upload/usuarios/tmp/';
			$file_name = MediaHelper::upload($file,$path,$file->getClientOriginalName());
			$heading = (new HeadingRowImport)->toArray($path.$file_name,'web_site');
			if(sizeof($heading) > 1){
				$statusCode = 422;
				$response = ['msg' => 'A planilha deve ter apenas uma página.'];
			}else{
				if($this->checkHeaderClassificacao($heading)){
					$errors = [];
					$niveis = [];
					$key = 2;
					$data_log = array(
						'administrador_id' => Auth::guard('admin')->id(),
						'registro_id' => null,
						'tabela' => 'classificacoes',
						'tipo' => 'import_start',
						'ip' => $request->ip(),
						'alteracoes' => ''
					);
					LogAdministrador::store($data_log);
					Excel::import(new ClassificacaoImport($key,$errors,$niveis),$path.$file_name,'web_site');
					foreach ($niveis as $key => $data_nivel){
						$nivel = Nivel::find($key);
						$nivel->fill($data_nivel);
						$nivel->save();
					}
					if(sizeof($errors) > 0){
						$statusCode = 422;
						$response = ['msg' => 'A importação aconteceu, mas teve os seguintes erros:', 'errors' => $errors];
					}else{
						$statusCode = 200;
						$response = ['msg' => 'Cadastrado com sucesso'];
					}
					$data_log = array(
						'administrador_id' => Auth::guard('admin')->id(),
						'registro_id' => null,
						'tabela' => 'classificacoes',
						'tipo' => 'import_end',
						'ip' => $request->ip(),
						'alteracoes' => ''
					);
					LogAdministrador::store($data_log);

				}else{
					$statusCode = 422;
					$response = ['msg' => 'Por favor, a planilha deve ter as seguinte colunas: CNPJ,PERFIL, NIVEL INICIAL,NIVEL ATUAL, DESCONTO, VPC,REBATE'];
				}
			}
			return response()->json($response,$statusCode);
		}
		return response()->json(['msg'=>'Arquivo não encontrado.'],422);
	}

	public function uploadSaldoVpc(Request $request){
		$params = $request->all();
		if(isset($params['file'])){
			$file = $request->file('file');
			$path = 'upload/usuarios/tmp/';
			$file_name = MediaHelper::upload($file,$path,$file->getClientOriginalName());
			$heading = (new HeadingRowImport)->toArray($path.$file_name,'web_site');
			if(sizeof($heading) > 1){
				$statusCode = 422;
				$response = ['msg' => 'A planilha deve ter apenas uma página.'];
			}else{
				if($this->checkHeaderSaldoVpc($heading)){
					$errors = [];
					$data_log = array(
						'administrador_id' => Auth::guard('admin')->id(),
						'registro_id' => null,
						'tabela' => 'saldo_vpc_usuario',
						'tipo' => 'import_start',
						'ip' => $request->ip(),
						'alteracoes' => ''
					);
					LogAdministrador::store($data_log);
					Excel::import(new SaldoVpcImport($errors),$path.$file_name,'web_site');
					if(sizeof($errors) > 0){
						$statusCode = 422;
						$response = ['msg' => 'A importação aconteceu, mas teve os seguintes erros:', 'errors' => $errors];
					}else{
						$statusCode = 200;
						$response = ['msg' => 'Cadastrado com sucesso'];
					}
					$data_log = array(
						'administrador_id' => Auth::guard('admin')->id(),
						'registro_id' => null,
						'tabela' => 'saldo_vpc_usuario',
						'tipo' => 'import_end',
						'ip' => $request->ip(),
						'alteracoes' => ''
					);
					LogAdministrador::store($data_log);

				}else{
					$statusCode = 422;
					$response = ['msg' => 'Por favor, a planilha deve ter as seguinte colunas: CNPJ,ANO,MES,SALDO,VALIDADE'];
				}
			}
			return response()->json($response,$statusCode);
		}
		return response()->json(['msg'=>'Arquivo não encontrado.'],422);
	}

	private function checkHeader($header){
		$expected_header = ['cnpj','nome_fantasia','razao_social','grupo','uf','cidade','perfil','nivel'];
		return $this->headerCheck($expected_header, $header);
	}

	private function checkHeaderClassificacao($header){
		$expected_header = ['cnpj','perfil','nivel_inicial','nivel_atual','desconto','vpc','rebate'];
		return $this->headerCheck($expected_header, $header);
	}

	private function checkHeaderMeta($header){
		$expected_header = ['cnpj','ano','mes','valor','valor_meta','valor_meta_trimestre','valor_falta_vpc','valor_falta_rebate','rebate_disponivel'];
		return $this->headerCheck($expected_header, $header);
	}

	private function checkHeaderSaldoVpc($header){
		$expected_header = ['cnpj','ano','mes','saldo','validade'];
		return $this->headerCheck($expected_header, $header);
	}

	private function headerCheck($expected_header, $header){
		if(sizeof($header) > 0){
			if(sizeof($header[0]) > 0){
				foreach ($expected_header as $item){
					if(!in_array($item,$header[0][0]))
						return false;
				}
				return true;
			}
		}
		return false;
	}

	public function export(Request $request)
	{
		if (!$this->hasPermission($this->role, 1)) {
			return response()->json(['error' => 'Unauthorized.'], 403);
		}
		$data = array(
			'administrador_id' => Auth::user()->id,
			'registro_id' => 0,
			'tabela' => 'usuarios',
			'tipo' => 'export',
			'ip' => $request->ip()
		);
		LogAdministrador::store($data);
		$query = DB::table('usuarios')->orderBy('usuarios.created_at','asc');
		$campos = ['usuarios.documento','usuarios.razao_social','usuarios.nome_fantasia','EMAIL','perfis_usuarios.nome as perfil','niveis.nome as nivel',
			'usuarios.conta_atualizada as primeiro_acesso'];
		$query->select($campos);
		$query->join('niveis','usuarios.nivel_id','=','niveis.id');
		$query->join('perfis_usuarios','niveis.perfil_id','=','perfis_usuarios.id');
		$query->whereNull('usuarios.deleted_at')->where([['ativo','=',1],['usuarios.teste','!=',1]]);
		$usuarios = $query->get()->toArray();
		$usuarios = array_map(function ($value) {
			return (array)$value;
		}, $usuarios);
		if(!file_exists('exports/'))
			mkdir('exports/', 0755);
		if(!file_exists('exports/usuarios-exports/'))
			mkdir('exports/usuarios-exports/', 0755);
		$file = 'exports/usuarios-exports/usuarios-'.date('Y-m-d-h-i-s').'.xls';
		$handler = fopen($file,'w');
		fclose($handler);
		$handler = fopen($file,'a');
		fwrite($handler,'<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>');
		fwrite($handler,'<body><table><thead><tr>');
		foreach ($usuarios as $key => $usuario){
			if(isset($usuario['documento'])){
				if(strlen($usuario['documento']) == 11)
					$usuario['documento'] = VariableHelper::mask($usuario['documento'],'###.###.###-##');
				else
					$usuario['documento'] = VariableHelper::mask($usuario['documento'],'##.###.###/####-##');
			}
			if($key == 0){
				$keys = array_keys($usuario);
				foreach ($keys as $i => $key){
					$keys[$i] = strtoupper($key);
				}
				fwrite($handler,'<td>'.implode('</td><td>',$keys).'</td>');
				fwrite($handler,'</tr></thead><tbody>');
			}
			fwrite($handler,'<tr><td>'.mb_strtoupper (implode('</td><td>',array_values($usuario)),'UTF-8').'</td></tr>');
		}
		fwrite($handler,'</tbody></table></body></html>');
		fclose($handler);
		return response()->download($file);
	}
}
