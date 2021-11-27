<?php

namespace App\Http\Controllers\Admin;

use App\Model\AcessoAdministrador;
use App\Http\Controllers\Controller;

class LogController extends Controller {

	protected $searchable = ['ip'];
	protected $editRoute = '';
	/**
	 * Instantiate a new controller instance.
	 *
	 */
	public function __construct()
	{
		$this->customFilter = true;
		$this->role = 1;
		$this->model = new AcessoAdministrador();
		$this->middleware('role:'.$this->role);
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$this->beforeAction();
		if(!$this->hasPermission($this->role,1))
			return view('errors.403',$this->data);
		return view('admin.log.acessos',$this->data);
	}

	public function customWhere($p_q,&$p_data){
		if(isset($p_q['q'])){
			$p_data->orWhereHas('administrador', function($query) use ($p_q){
				$query->where('nome','like','%'.$p_q['q'].'%');
			});
		}
	}
}
