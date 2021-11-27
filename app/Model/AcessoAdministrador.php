<?php

namespace App\Model;

use App\Model\Administrador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcessoAdministrador extends Model
{
    use SoftDeletes;
	protected $table = 'log_acesso_administrador';
    public $timestamps  = false;

    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'ip','data','administrador_id'
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
	public function administrador(){
		return $this->belongsTo('App\Model\Administrador');
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function get(){
		return $this->with('administrador');
	}

    /**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data){
		$acesso = new self;
		$acesso->fill($data);
		if(isset($data['administrador_id']) && ($administrador = Administrador::find($data['administrador_id'])) != null && $acesso->save()){
			$administrador->acessos()->associate($acesso);
			$administrador->save();
			return $acesso->save();
		}
		return false;
	}
}
