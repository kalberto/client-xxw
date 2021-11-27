<?php

namespace App\Model\Web;

use Illuminate\Database\Eloquent\Model;

class AssuntoVPC extends Model
{
    protected $table = 'assuntos_vpc';

	public $timestamps = false;

	protected $hidden = ['campos'];

	public function getCamposAttribute($value){
		if(isset($value))
			return json_decode($value);
		return [];
	}
}
