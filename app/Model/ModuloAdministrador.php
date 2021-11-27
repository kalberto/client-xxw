<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuloAdministrador extends Model
{
	use SoftDeletes;
	public $timestamps = false;

    protected $table = 'modulo_administrador';
	protected $fillable = ['nome','icone','modulo_list','modulo_url','obrigatorio','order'];
    protected $hidden = [];

	public static function getAll(){
		return ModuloAdministrador::query()->orderBy('order')->get();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function mod_adm_permissao(){
		return $this->hasMany('App\Model\ModAdmPermissao','mod_adm_id');
	}
}
