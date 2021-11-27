<?php

namespace App\Http\Controllers\Admin;

use App\Model\AcessoAdministrador;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LocalizacaoController extends Controller {

	/**
	 * Instantiate a new controller instance.
	 *
	 */
	public function __construct()
	{
		$this->role = 1;
		$this->middleware('role:'.$this->role);
	}

	public function estados(){
		$registros = DB::table('estados')->select(['id','nome'])->get()->toArray();
		return response()->json(['registros' => $registros],200);
	}

	public function cidades($id){
		$registros = DB::table('cidades')->select(['id','nome'])->where('estado_id','=',$id)->get()->toArray();
		return response()->json(['registros' => $registros],200);
	}
}
