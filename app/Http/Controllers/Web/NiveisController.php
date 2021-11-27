<?php

namespace App\Http\Controllers\Web;


use App\Helpers\PaginatorHelper;
use App\Model\Canais\Conteudo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NiveisController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function load(Request $request) {
		$user = Auth::user()->documento;
		$registros = DB::table('usuarios')->select('usuarios.nivel_id as nivel_id')->where('documento',$user)
		->join('niveis','niveis.id','nivel_id')
		->select(['niveis.nome as nome','niveis.desconto as desconto','niveis.vpc as vpc','niveis.rebate as rebate',
			'perfis_usuarios.nome as nome_perfil'])
		->join('perfis_usuarios','perfis_usuarios.id','niveis.perfil_id')
		->get()->toArray();
		foreach($registros as $registro){
			$response = [
				'nivel' => $registro->nome,
				'perfil' => $registro->nome_perfil,
				'desconto' => Auth::user()->desconto,
				'vpc' => Auth::user()->vpc,
				'rebate' => Auth::user()->rebate
			];
		}
		return response()->json($response, 200);
	}

}
