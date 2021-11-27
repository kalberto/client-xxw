<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ResultadoMes extends Model
{
    protected $table = 'resultados_mes';
	protected $fillable = ['documento','valor_mes','valor_falta_vpc','valor_falta_rebate','rebate_disponivel','metas_usuario'];
}
