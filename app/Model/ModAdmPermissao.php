<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ModAdmPermissao extends Model
{
	protected $table = 'mod_adm_permissao';

	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'mod_adm_id','permissao_id'
	];

	protected $hidden = [
		'created_at','updated_at','deleted_at'
	];

	public function permissao(){
		return $this->belongsTo('App\Model\Permissao');
	}

	public function modulo(){
		return $this->belongsTo('App\Model\ModuloAdministrador','mod_adm_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function perfis(){
		return $this->belongsToMany('App\Model\Perfil','perfil_mod_adm_permissao','mod_adm_perm_id','perfil_id');
	}

	public static function getAll(){
		return ModuloAdministrador::with('mod_adm_permissao.permissao')->get();
		//return ModAdmPermissao::with(['modulo','permissao'])->groupBy('mod_adm_id')->get()->makeVisible('id');
	}
}
