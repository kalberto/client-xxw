<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    public $timestamps = false;

    protected $table = 'niveis';
	protected $fillable = ['nome','meta_mes','meta_trimestre','desconto','vpc','rebate','perfil_id','deleted_at'];
}
