<?php

namespace App\Model\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SaldoVpc extends Model
{
    protected $table = 'saldo_vpc_usuario';

	public $timestamps = false;

	protected $hidden = ['campos'];

	public function getSaldoProvisionadoAttribute(){
		$saldo = DB::table("saldo_vpc_utilizado")
		                     ->select(DB::raw('sum(provisionado) as provisionado'))
		                     ->where([['saldo_id','=',$this->id],['provisionado','!=',null],['provisionado','!=',0]])->first();
		if(isset($saldo->provisionado))
			return $saldo->provisionado;
		return 0;
	}

	public function getSaldoDisponivelAttribute(){
		return $this->saldo_vpc - $this->saldo_provisionado;
	}
}
