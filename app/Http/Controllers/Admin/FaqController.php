<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Canais\Faq;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Faq\CreateRequest;
use App\Http\Requests\Admin\Faq\EditRequest;
use App\Http\Requests\Admin\Faq\ShowRequest;
use App\Http\Requests\Admin\Faq\IdRequest;
use App\Http\Requests\Admin\Faq\DeleteRequest;

class FaqController extends Controller
{
	protected $searchable = ['pergunta','resposta'];
	protected $editRoute = 'admin.faqs.editar';

	/**
	 * Instantiate a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->customFilter = false;
		$this->role = 10;
		$this->model = new Faq();
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
		return view('admin.faqs.faqs',$this->data);
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
		return view('admin.faqs.faqs-create',$this->data);
	}

	public function store(CreateRequest $request)
	{
		$data = $request->all();
		if(Faq::store($data, $request->ip())){
			$statusCode = 200;
			$response = [
				'msg' => 'Registro salvo com sucesso',
				'url' => '/admin/faqs'
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
	 * @param  integer $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(IdRequest $request, $id)
	{
		return response()->json(['registro' => Faq::show($id)], 200);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  integer  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$this->beforeAction();
		if(!$this->hasPermission($this->role,2))
			return view('errors.403',$this->data);
		$this->data['id'] = $id;
		return view('admin.faqs.faqs-edit',$this->data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param EditRequest|Request $request
	 * @param  integer $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(EditRequest $request, $id)
	{
		$data = $request->all();
		$data['id'] = $id;
		if(Faq::edit($data, $request->ip())){
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

	public function destroy(DeleteRequest $request,$id){
		$registro = Faq::find($id);
		$registro->remove($request->ip());
		return response()->json(['msg' => 'Registro deletado com sucesso!'],200);
	}
}
