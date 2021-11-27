<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class VPCHelper {

	static $campos = [
		'nome' => [ 'campo' => 'nome', 'tipo' => 'text','label' => 'Nome da Solicitação *'],
		'custo' => [ 'campo' => 'custo', 'tipo' => 'money','label' => 'Custo *'],
		'data_inicio' => [ 'campo' => 'data_inicio', 'tipo' => 'date','label' => 'Data início *'],
		'data_fim' => [ 'campo' => 'data_fim', 'tipo' => 'date','label' => 'Data fim *'],
		'objetivo' => [ 'campo' => 'objetivo', 'tipo' => 'text','label' => 'Objetivo *'],
		'publico_alvo' => [ 'campo' => 'publico_alvo', 'tipo' => 'text','label' => 'Público-alvo *'],
		'descricao' => [ 'campo' => 'descricao', 'tipo' => 'text','label' => 'Descrição *'],
		'modelos' => [ 'campo' => 'modelos', 'tipo' => 'text','label' => 'Modelos/Formatos *'],
		'anexos' => [ 'campo' => 'anexos', 'tipo' => 'files','label' => 'Anexos *'],
		'comprovantes' => [ 'campo' => 'comprovantes', 'tipo' => 'files','label' => 'Comprovantes *'],

	];

	static $edicaoCampos = [
		'SOLICITADA' => [],
		'REVISÃO' => ['custo','data_inicio','data_fim','objetivo','publico_alvo','descricao','modelos','anexos'],
		'REPROVADA' => [],
		'APROVADO' => ['comprovantes'],
		'COMPROVAÇÃO' => ['comprovantes'],
		'COMPROVADO' => [],
		'PAGO'=> [],
		'CANCELADA' => []
	];

	static $rules = [
		'nome' => [ 'dados.nome' => ['required','max:50']],
		'custo' => [ 'dados.custo' => ['required']],
		'data_inicio' => [ 'dados.data_inicio' => ['required','date','date_format:Y-m-d']],
		'data_fim' => [ 'dados.data_fim' => ['required','date','date_format:Y-m-d','after_or_equal:dados.data_inicio']],
		'objetivo' => [ 'dados.objetivo' => ['required']],
		'publico_alvo' => [ 'dados.publico_alvo' => ['required']],
		'descricao' => [ 'dados.descricao' => ['required']],
		'modelos' => [ 'dados.modelos' => ['required']],
		'anexos' => [ 'anexos' => ['required','array'],'anexos.*' =>[ 'file','mimes:jpg,jpeg,png,pdf','max:2000']],
		'comprovantes' => [ 'comprovantes' => ['required'],'comprovantes.*' =>[ 'file','mimes:jpg,jpeg,png,pdf','max:2000']],
	];

	public static function getCampos($array,$status_id = null){
		$registros = [];
		$status = false;
		if(isset($status_id)){
			$status = DB::table('status_vpc')->find($status_id)->nome;
		}
		foreach ($array as $item){
			if(isset(VPCHelper::$campos[$item])){
				$campos = VPCHelper::$campos[$item];
				if($status !== false){
					if($item == 'custo' || $item == 'data_inicio')
						$campos['cant_edit'] = true;
					else{
						if(sizeof(VPCHelper::$edicaoCampos[$status]) == 0)
							$campos['cant_edit'] = true;
						elseif (!in_array($item,VPCHelper::$edicaoCampos[$status]))
							$campos['cant_edit'] = true;
					}
				}
				$registros[] = $campos;
			}
		}
		return $registros;
	}

	public static function getRules($array,$status_nome = null){
		$registros = [];
		foreach ($array as $item){
			if(isset($status_nome)){
				if(in_array($item,VPCHelper::$edicaoCampos[$status_nome]) && isset(VPCHelper::$rules[$item]))
					$registros = array_merge($registros, VPCHelper::$rules[$item]);
			}else{
				if(isset(VPCHelper::$rules[$item]))
					$registros = array_merge($registros, VPCHelper::$rules[$item]);
			}
		}
		return $registros;
	}
}
