<?php

namespace App\Http\Controllers\Web;


use App\Helpers\MonthHelper;
use App\Helpers\PaginatorHelper;
use App\Helpers\VariableHelper;
use App\Model\Canais\Conteudo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MetasController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function load(Request $request) {
		setlocale(LC_MONETARY, 'pt_BR');
		$documento = Auth::user()->documento;
		$registro = DB::table("resultados_mes")->where([['resultados_mes.documento','=',$documento],['metas_usuario.ano','=',date("Y")],['metas_usuario.mes','=',date("n")]])
		                                       ->join('metas_usuario','resultados_mes.metas_usuario','=','metas_usuario.id')->select(['resultados_mes.valor_mes as valor_mes','resultados_mes.valor_falta_vpc as valor_falta_vpc','resultados_mes.valor_falta_rebate as valor_falta_rebate',
			'resultados_mes.valor_falta_vpc as valor_falta_vpc','updated_at','metas_usuario.ano as ano','metas_usuario.mes as mes',
				'metas_usuario.meta_mes as meta_mes','metas_usuario.meta_trimestre as meta_trimestre'])
		->first();
		if(isset($registro)){
			$metas = DB::table("metas_usuario")->select('id')->whereIn('mes',MonthHelper::getMonthTrimestre($registro->mes))->where([['metas_usuario.documento','=',$documento],['metas_usuario.ano','=',date("Y")]])->pluck('id')->toArray();
			$valor_trimestre = DB::table('resultados_mes')->select(DB::raw('SUM(resultados_mes.valor_mes) as valor_trimestre'))->whereIn('metas_usuario',$metas)->pluck('valor_trimestre')->first();
			$date = $registro->updated_at;
			VariableHelper::convertDateFormat($date,'Y-m-d H:i:s','d.m.Y');
			$response = [
				'ano' => $registro->ano,
				'mes' => $registro->mes,
				'meta_mes' => number_format($registro->meta_mes,2,',','.'),
				'meta_mes_value' => $registro->meta_mes,
				'meta_trimestre' => number_format($registro->meta_trimestre,2,',','.'),
				'meta_trimestre_value' => $registro->meta_trimestre,
				'valor_mes' => number_format($registro->valor_mes,2,',','.'),
				'valor_mes_value' => $registro->valor_mes,
				'valor_trimestre' => number_format($valor_trimestre,2,',','.'),
				'valor_trimestre_value' => $valor_trimestre,
				'valor_falta_vpc' => number_format($registro->valor_falta_vpc,2,',','.'),
				'valor_falta_vpc_value' => $registro->valor_falta_vpc,
				'valor_falta_rebate' => number_format($registro->valor_falta_rebate,2,',','.'),
				'valor_falta_rebate_value' => $registro->valor_falta_rebate,
				'updated_at' => $date
			];
			return response()->json($response, 200);
		}
		else
			return response()->json(['msg'=>'Nenhum registro encontrado.','error' => true],200);
	}

	public function anos(){
		setlocale(LC_MONETARY, 'pt_BR');
		$documento = Auth::user()->documento;
		$anos = DB::table("metas_usuario")->select('ano')->where('documento','=',$documento)->groupBy(['ano'])->pluck('ano')->toArray();
		return response()->json($anos);
	}

	public function trimestres($ano){
		$documento = Auth::user()->documento;
		$meses = DB::table("metas_usuario")->select('mes')->where([['documento','=',$documento],['ano','=',$ano]])->groupBy(['mes'])->pluck('mes')->toArray();
		$trimestres = [];
		foreach ($meses as $mes){
			if(!isset($trimestres[MonthHelper::getTrimestreMonth($mes)])){
				$trimestres[MonthHelper::getTrimestreMonth($mes)] = [
					'nome' => MonthHelper::getTrimestreMonth($mes),
					'array' => [['nome' => MonthHelper::getMonth($mes),'valor' => $mes]]
				];
			}else{
				$trimestres[MonthHelper::getTrimestreMonth($mes)]['array'][] = ['nome' => MonthHelper::getMonth($mes),'valor' => $mes];
			}
		}
		return $trimestres;
	}

	public function meses($ano){
		setlocale(LC_MONETARY, 'pt_BR');
		$documento = Auth::user()->documento;
		$meses = DB::table("metas_usuario")->select('mes')->where([['documento','=',$documento],['ano','=',$ano]])->groupBy(['mes'])->pluck('mes')->toArray();
		$retorno = [];
		foreach ($meses as $mes){
			$m = [
				'nome' => MonthHelper::getMonth($mes),
				'valor' => $mes
			];
			$retorno[] = $m;
		}
		return response()->json($retorno);
	}

	public function meta($ano,$mes){
		setlocale(LC_MONETARY, 'pt_BR');
		$documento = Auth::user()->documento;
		$registro = DB::table("resultados_mes")->where([['resultados_mes.documento','=',$documento],['metas_usuario.ano','=',$ano],['metas_usuario.mes','=',$mes]])
		              ->join('metas_usuario','resultados_mes.metas_usuario','=','metas_usuario.id')->select(['resultados_mes.valor_mes as valor_mes','resultados_mes.valor_falta_vpc as valor_falta_vpc','resultados_mes.valor_falta_rebate as valor_falta_rebate',
				'resultados_mes.valor_falta_vpc as valor_falta_vpc','resultados_mes.rebate_disponivel',
				'metas_usuario.ano as ano','metas_usuario.mes as mes','metas_usuario.meta_mes as meta_mes','metas_usuario.meta_trimestre as meta_trimestre'])
		              ->first();
		if(isset($registro)){
			$metas = DB::table("metas_usuario")->select('id')->whereIn('mes',MonthHelper::getMonthCurrentTrimestre($registro->mes))->where([['metas_usuario.documento','=',$documento],['metas_usuario.ano','=',$ano]])->pluck('id')->toArray();
			$valor_trimestre = DB::table('resultados_mes')->select(DB::raw('SUM(resultados_mes.valor_mes) as valor_trimestre'))->whereIn('metas_usuario',$metas)->pluck('valor_trimestre')->first();
			$metaMes = [
				'meta_valor' => $registro->meta_mes,
				'meta_formatado' => number_format($registro->meta_mes,2,',','.'),
				'realizado_valor' => $registro->valor_mes,
				'realizado_formatado' => number_format($registro->valor_mes,2,',','.'),
				'valor_falta_vpc' => number_format($registro->valor_falta_vpc,2,',','.'),
				'valor_falta_rebate' => number_format($registro->valor_falta_rebate,2,',','.'),
				'rebate_disponivel' => number_format($registro->rebate_disponivel,2,',','.')
			];
			$metaTrimestre = [
				'meta_valor' => $registro->meta_trimestre,
				'meta_formatado' => number_format($registro->meta_trimestre,2,',','.'),
				'realizado_valor' => $valor_trimestre,
				'realizado_formatado' => number_format($valor_trimestre,2,',','.'),
			];
			$response = [
				'meta_mes' => $metaMes,
				'meta_trimestre' => $metaTrimestre,

			];
			return response()->json($response, 200);
		}
		else
			return response()->json(['msg'=>'Nenhum registro encontrado.','error' => true],200);
	}

	public function detalhamentoAno($ano){
		setlocale(LC_MONETARY, 'pt_BR');
		$documento = Auth::user()->documento;
		$registros = DB::table("resultados_mes")->where([['resultados_mes.documento','=',$documento],['metas_usuario.ano','=',$ano]])
		              ->join('metas_usuario','resultados_mes.metas_usuario','=','metas_usuario.id')
		              ->select(['resultados_mes.valor_mes as valor_mes','metas_usuario.mes as mes','metas_usuario.meta_mes as meta_mes'])
		              ->orderBy('metas_usuario.mes')->get();
		$meses = $metas = $valores = [0,0,0,0,0,0,0,0,0,0,0,0];
		if(isset($registros)){
			foreach ($registros as $registro){
				$index = $registro->mes - 1;
				$valores[$index] = $registro->valor_mes;
				$meses[$index] = $registro->mes;
				$metas[$index] = $registro->meta_mes;
			}
		}
		$response = [
			'meses' => $meses,
			'metas' => $metas,
			'valores' => $valores,
		];
		return response()->json($response);
	}

}
