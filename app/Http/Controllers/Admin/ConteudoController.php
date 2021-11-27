<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MediaHelper;
use App\Http\Requests\Admin\Conteudo\UploadRequest;
use App\Model\Conteudo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Conteudo\CreateRequest;
use App\Http\Requests\Admin\Conteudo\EditRequest;
use App\Http\Requests\Admin\Conteudo\IdRequest;
use App\Http\Requests\Admin\Conteudo\DeleteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Model\Media;

class ConteudoController extends Controller
{

    protected $searchable = ['nome','titulo','autor'];
	protected $page = 'conteudos';
	protected $editRoute = 'admin.conteudos.editar';

    public function __construct()
	{
		$this->role = 6;
		$this->model = new Conteudo();
		$this->customFilter = false;
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
		return view('admin.conteudos.conteudos',$this->data);
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
		return view('admin.conteudos.conteudos-create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $data = $request->all();
		if(Conteudo::store($data, $request->ip())){
			$statusCode = 200;
			$response = [
				'msg' => 'Registro salvo com sucesso',
				'url' => '/admin/conteudos'
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
	 * @param IdRequest $request
	 * @param $id
	 *
	 * @return \Illuminate\Http\Response
	 * @internal param Conteudo $conteudo
	 */
    public function show(IdRequest $request, $id)
    {
        $registro = Conteudo::where('evento',0)->with('media.media_root')->find($id);
		return response()->json(['registro' => $registro],200);
    }

	public function ativo(IdRequest $request, $id){
		$registro = Conteudo::find($id);
		if(isset($registro)){
			$registro->ativo = !$registro->ativo;
			$registro->save();
			$statusCode = 200;
			$response = [
				'msg' => 'Salvo com sucesso.'
			];
		}else{
			$statusCode = 404;
			$response = [
				'msg' => 'Não foi possível encontrar esse Curso.'
			];
		}
		return response()->json($response,$statusCode);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Http\Response
	 * @internal param Conteudo $conteudo
	 */
    public function edit($id)
    {
        $this->beforeAction();
		$this->data['id'] = $id;
		if (!$this->hasPermission($this->role, 2)) {
			return view('errors.403', $this->data);
		}
		return view('admin.conteudos.conteudos-edit', $this->data);
    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param EditRequest|Request $request
	 * @param $id
	 *
	 * @return \Illuminate\Http\Response
	 * @internal param Conteudo $conteudo
	 */
    public function update(EditRequest $request, $id)
    {
        $data = $request->all();
		if (Conteudo::edit($id, $data, $request->ip())) {
			$statusCode = 200;
			$response = [
				'msg' => 'Registro editado com sucesso.',
				'url' => '/admin/conteudos'
			];
		} else {
			$statusCode = 500;
			$response = [
				'msg' => 'Ocorreu um erro ao editar o registro.'
			];
		}
		return response()->json($response,$statusCode);
    }

    public function saveFiles(UploadRequest $request,$id){
    	$data = $request->all();
    	foreach ($data['documentos'] as $documento){
    		$mimeType = $documento->getClientOriginalExtension();
		    $nome_original = $documento->getClientOriginalName();
		    $file = MediaHelper::upload($documento, 'upload/conteudo-evento/documentos', str_replace(".".$documento->getClientOriginalExtension(),'',$documento->getClientOriginalName()));
		    DB::table('documentos_conteudos')->insert(['file' => $file,'type' => $mimeType,'conteudo_id' => $id,'nome_original' => $nome_original]);
	    }
	    return response()->json(['msg' => 'Cadastrado com sucesso.'],200);
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param DeleteRequest $request
	 * @param $id
	 *
	 * @return \Illuminate\Http\Response
	 * @internal param Conteudo $conteudo
	 */
    public function destroy(DeleteRequest $request,$id)
    {
        $registro = Conteudo::find($id);
		$registro->remove($request->ip());
		$item = DB::table('media')->where('conteudo_id',$id)->select(['conteudo_id'])->first();
		if($item != null)
			DB::table('media')->where('conteudo_id',$item->conteudo_id)->update(['conteudo_id' => null]);
		return response()->json(['msg' => 'Registro deletado com sucesso!'],200);
    }

    public function deleteDocumento($id){
    	$documento = DB::table('documentos_conteudos')->where('id','=',$id)->first();
    	if(isset($documento)){
		    if(MediaHelper::delete_file('upload/conteudo-evento/documentos/',$documento->file)){
			    DB::table('documentos_conteudos')->delete($id);
				    return response()->json(['msg' => 'Documento deletado com sucesso.'],200);
		    }

		    return response()->json(['msg' => 'Aconteceu um imprevisto ao deletar o arquivo'],500);
	    }
	    return response()->json(['msg' => 'Documento não encontrado'],404);
    }

    public function checkUrl(Request $request,$id = null)
	{
		$data = $request->only('slug');
		if(isset($data["slug"]) && $data["slug"] != "") {
			$slug  = Str::slug( $data['slug'] );
			if(isset($id))
				$count = Conteudo::where( [ [ 'id', '!=', $id ], [ 'slug', '=', $slug ] ] )->count();
			else
				$count = Conteudo::where('slug', '=',$slug)->count();
			if ( $count > 0 ) {
				$statusCode = 422;
				$response   = [
					'error_validate' => [
						'slug' => 'Já possui um evento/conteúdo com essa slug'
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

    public function only($id = null){
        if($id != null)
		    $registros = DB::table('conteudos')->select(['id','nome'])->where('id','!=',$id)->where('evento',0)->where('deleted_at',null)->get();
		else
            $registros = DB::table('conteudos')->select(['id','nome'])->where('evento',0)->where('deleted_at',null)->get();
		return response()->json(['registros' => $registros],200);
	}

	public function conteudosSelect($id = null){
    	$query = DB::table('conteudos')->select(['id','nome'])->where('deleted_at','=',null);
        if($id != null)
		    $query->where('id','!=',$id);
		$registros = $query->get();
		return response()->json(['registros' => $registros],200);
	}

	public function all(){
		$registros = DB::table('conteudos')->orderBy('nome')->get();
		return response()->json(['registros' => $registros],200);
	}

	public function allMedias($id = null){
		$registros = Media::with(['media_root'])
			->where('media_root_id',2)
			->where(function($query) use ($id) {
				$query
					->orWhere('conteudo_id',$id)
					->orWhere('conteudo_id',null);
			})
			->get()->makeVisible('id');
		return response()->json(['registros' => $registros],200);
	}

}
