<?php

namespace App\Model;

use App\Http\Traits\AdminModelLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
	use AdminModelLog, SoftDeletes;

	protected $table = 'categorias';
	protected $fillable = [
		'nome', 'slug','ativo'
	];

	public function get()
	{
		return $this;
	}
	
	public function setNomeAttribute($value)
	{
		$this->attributes['nome'] = mb_strtoupper($value, "UTF-8");
    }


	public static function criar($data, $ip)
	{
		$model = self::firstOrCreate(
			[
				'id' => isset($data['id']) ? $data['id'] : ''
			],
			[
				"nome" => $data["nome"],
				"slug" => isset($data["slug"]) ? $data["slug"] : '',
			]
		);
		$model->saveLog($ip, 'insert',$data);
		return $model;
	}

	public static function show($id)
	{
		$model = self::find($id);
		if (!isset($model))
			return null;
		return $model;
	}

	public static function atualizar($data, $ip)
	{
		$model = self::updateOrCreate(
			[
				"id" => $data["id"]
			],
			[
				"nome" => $data["nome"],
				"slug" => isset($data["slug"]) ? $data["slug"] : '',
			]
		);
		$model->saveLog($ip, 'update',$data);
		return $model;
	}

	public function remove($ip){
		$this->saveLog($ip,'delete',[]);
		$this->delete();
	}
}
