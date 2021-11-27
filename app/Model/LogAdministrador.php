<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogAdministrador extends Model
{
    use SoftDeletes;
    protected $table = 'log_acoes_administrador';

    	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'registro_id','tabela','tipo','ip','administrador_id','alteracoes'
	];
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo+
	 */
	public function administrador(){
		return $this->belongsTo('App\Model\Administrador');
	}

	public function setAlteracoesAttribute($value)
	{
		$this->attributes['alteracoes'] = json_encode($value);
	}

	public static function saveDB($registro_id,$table,$tipo,$ip){
		$data_log = array(
			'administrador_id' => Auth::guard('admin')->user()->id,
			'registro_id' => $registro_id,
			'tabela' => $table,
			'tipo' => $tipo,
			'ip' => $ip
		);
		return LogAdministrador::store($data_log);
	}

	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data){
		$log = new self;
		$log->fill($data);
		return $log->save();
	}
}
