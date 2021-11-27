<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permissao extends Model
{
	use SoftDeletes;

	protected $table = 'permissoes';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'nome','required_id'
	];

	protected $hidden = [
		'id','deleted_at'
	];

	protected $casts = [
		//'status' => 'boolean',
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function mod_adm_permissao(){
		return $this->hasMany('App\Model\ModAdmPermissao');
	}
}
