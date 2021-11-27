<?php

namespace App\Model\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcessoUsuario extends Model
{
	use SoftDeletes;
	protected $table = 'logs_acessos_usuarios';
	public $timestamps  = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'ip', 'data', 'documento'
	];
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = ['id'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function usuario()
	{
		return $this->belongsTo('App\Model\Web\Usuario','documento');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function get()
	{
		return $this->with('usuario');
	}
}
