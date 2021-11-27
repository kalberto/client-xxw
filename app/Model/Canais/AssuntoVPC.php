<?php

namespace App\Model\Canais;

use Illuminate\Database\Eloquent\Model;

class AssuntoVPC extends Model
{
    protected $table = 'assuntos_vpc';

	public $timestamps = false;

	protected $fillable = [
		'nome','porcentagem','campos'
	];

	public function setCamposAttribute($value)
	{
		$this->attributes['campos'] = json_encode($value);
	}

	public function getCamposAttribute($value){
		if(isset($value))
			return json_decode($value);
		return [];
	}
}
