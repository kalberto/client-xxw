<?php

namespace App\Http\Traits;

use App\Model\LogAdministrador;
use Illuminate\Support\Facades\Auth;

trait AdministradorModelLog {

	protected function getPrimaryKeyForLog(){
		$primary_key = "";
		$name = $this->getKeyName();
		if(is_array($name)){
			foreach($name as $value){
				$primary_key .= $value.":".$this->$value.";";
			}
		}else{
			$primary_key = $this->$name;
		}
		return $primary_key;
	}

	protected function saveLog($ip,$tipo,$dados = null){
		if(Auth::guard('admin')->id() !== null){
			$data_log = array(
				'administrador_id' => Auth::guard('admin')->id(),
				'registro_id' => $this->getPrimaryKeyForLog(),
				'tabela' => $this->getTable(),
				'tipo' => $tipo,
				'ip' => $ip == false ? '127.0.0.1' : $ip,
				'alteracoes' => isset($dados) ? $dados : ''
			);
			LogAdministrador::store($data_log);
		}
	}
}