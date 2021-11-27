<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Categoria\CreateRequest;
use App\Http\Requests\Admin\Categoria\EditRequest;
use App\Http\Requests\Admin\Categoria\IdRequest;
use App\Http\Requests\Admin\Categoria\DeleteRequest;
use App\Model\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriaController extends Controller
{
	protected $searchable = ['categoria', 'titulo', 'sub_titulo'];
	protected $page = 'categorias';
	protected $editRoute = 'admin.categorias.editar';

	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->customFilter = false;
		$this->role = 8;
		$this->model = new Categoria;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$this->beforeAction();
		if (!$this->hasPermission($this->role, 1))
			return view('errors.403', $this->data);
		return view('admin.categorias.categorias', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$this->beforeAction();
		if (!$this->hasPermission($this->role, 2))
			return view('errors.403', $this->data);
		return view('admin.categorias.categorias-create', $this->data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CreateRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(CreateRequest $request)
	{
		$data = $request->all();
		Categoria::criar($data, $request->ip());
		$response = [
			'msg' => 'Registro salvo com sucesso',
			'url' => '/admin/categorias'
		];
		return response()->json($response);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param ShowRequest $request
	 * @param $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(IdRequest $request, $id)
	{
		$registro = Categoria::find($id);
		return response()->json(['registro' => $registro],200);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$this->beforeAction();
		if (!$this->hasPermission($this->role, 2))
			return view('errors.403', $this->data);
		$this->data['id'] = $id;
		return view('admin.categorias.categorias-edit', $this->data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param EditRequest $request
	 * @param $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(EditRequest $request, $id)
	{
		$data = $request->all();
		$data['id'] = $id;
		Categoria::atualizar($data, $request->ip());
		$statusCode = 200;
		$response = [
			'msg' => 'Registro salvo com sucesso'
		];
		return response()->json($response, $statusCode);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param DeleteRequest $request
	 * @param $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(DeleteRequest $request,$id)
    {
		$registro = Categoria::find($id);
		$registro->remove($request->ip());
		return response()->json(['msg' => 'Registro deletado com sucesso!'],200);
    }

	public function checkUrl(Request $request,$id = null)
	{
		$data = $request->all();
		if(isset($data["slug"]) && $data["slug"] != "") {
			$slug  = Str::slug( $data['slug'] );
			if(isset($id))
				$count = Categoria::where( [ [ 'id', '!=', $id ], [ 'slug', '=', $slug ] ] )->count();
			else
				$count = Categoria::where('slug', '=',$slug)->count();
			if ( $count > 0 ) {
				$statusCode = 422;
				$response   = [
					'error_validate' => [
						'slug' => 'Essa slug já está cadastrada.'
					]
				];
			} else {
				$statusCode = 200;
				$response   = [
					'field' => $slug,
					'validate' => [
						'slug'  => 'Disponível'
					]
				];
			}
		}else{
			$statusCode = 422;
			$response = [
				'error_validate' => [
					'slug' => 'Obrigatório.'
				]
			];
		}
		return response()->json($response, $statusCode);
	}

	public function checkUrlAll(Request $request){
		if(isset($request["slug"]) && $request["slug"] != ""){
			$slug = Str::slug($request['slug']);
			$count = Categoria::where('slug','=',$slug)->count();
			if($count > 0){
				$statusCode = 422;
				$response = [
					'errors' => [
						'slug' => 'Essa slug já está cadastrada.'
					]
				];
			}else{
				$statusCode = 200;
				$response = [
					'field' => $slug,
					'slug' => 'Disponível'
				];
			}
		}else{
			$statusCode = 422;
			$response = [
				'errors' => [
					'slug' => 'Obrigatório.'
				]
			];
		}
		return response()->json($response, $statusCode);
	}

	public function all(){
		$registros = DB::table('categorias')->orderBy('nome')->get();
		return response()->json(['registros' => $registros],200);
	}
}
