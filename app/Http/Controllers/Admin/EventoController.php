<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\Evento\CreateRequest;
use App\Http\Requests\Admin\Evento\EditRequest;
use App\Http\Requests\Admin\Evento\IdRequest;
use App\Http\Requests\Admin\Conteudo\DeleteRequest;
use App\Http\Controllers\Controller;
use App\Model\Conteudo;
use App\Model\Evento;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
	protected $searchable = ['nome','titulo','autor'];
	protected $page = 'eventos';
	protected $editRoute = 'admin.eventos.editar';

    public function __construct()
	{
		$this->role = 7;
		$this->model = new Evento();
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
		return view('admin.eventos.eventos',$this->data);
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
		return view('admin.eventos.eventos-create', $this->data);
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
        $data['evento'] = 1;
		if(Evento::store($data, $request->ip())){
			$statusCode = 200;
			$response = [
				'msg' => 'Registro salvo com sucesso',
				'url' => '/admin/eventos'
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(IdRequest $request, $id)
    {
        $registro = Evento::where('evento',1)->with('media.media_root')->find($id)->append(['perfis_id','niveis_id']);
		return response()->json(['registro' => $registro],200);
    }

	public function ativo(IdRequest $request, $id){
		$registro = Evento::find($id);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->beforeAction();
		$this->data['id'] = $id;
		if (!$this->hasPermission($this->role, 2)) {
			return view('errors.403', $this->data);
		}
		return view('admin.eventos.eventos-edit', $this->data);
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
		if (Evento::edit($id, $data, $request->ip())) {
			$statusCode = 200;
			$response = [
				'msg' => 'Registro editado com sucesso.',
				'url' => '/admin/eventos'
			];
		} else {
			$statusCode = 500;
			$response = [
				'msg' => 'Ocorreu um erro ao editar o registro.'
			];
		}
		return response()->json($response,$statusCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request,$id)
    {
        $registro = Conteudo::find($id);
        $evento  = Evento::find($registro['id']);
        $lista = $evento->listasRsvp()->first();
        if(isset($lista)){
            $lista->perfis()->detach();
            $lista->niveis()->detach();
            $lista->convidados()->detach();
        }
        $evento->listasRsvp()->delete();
		$registro->remove($request->ip());
		$item = DB::table('media')->where('conteudo_id',$id)->select(['conteudo_id'])->first();
		if($item != null)
			DB::table('media')->where('conteudo_id',$item->conteudo_id)->update(['conteudo_id' => null]);
		return response()->json(['msg' => 'Registro deletado com sucesso!'],200);
    }

    public function all($id = null){
        if($id != null)
		    $registros = DB::table('conteudos')->select(['id','nome'])->where('id','!=',$id)->where('evento',1)->where('deleted_at',null)->get();
		else
            $registros = DB::table('conteudos')->select(['id','nome'])->where('evento',1)->where('deleted_at',null)->get();
		return response()->json(['registros' => $registros],200);
	}

}
