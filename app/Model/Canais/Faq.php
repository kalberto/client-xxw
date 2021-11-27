<?php

namespace App\Model\Canais;

use App\Helpers\VariableHelper;
use App\Http\Traits\AdminModelLog;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

/**
 * @method static Faq find($id)
**/
class Faq extends Authenticatable
{
	use AdminModelLog;

	protected $table = 'faqs';
	protected $fillable = ['pergunta','resposta','perfil_id'];
	protected $hidden = ['id','created_at','updated_at'];

	/**
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function get(){
		return $this;
	}

	public static function store($data,$ip){
		$registro = new self();
		$registro->fill($data);
		$registro->save();
		$registro->saveLog($ip,'insert',$data);
		return $registro;
	}

	public static function edit($data,$ip) {
		$registro = self::find($data['id']);
		$registro->fill($data);
		$registro->saveLog($ip,'update',$data);
		$registro->save();
		return $registro;
	}

	public static function show($id)
	{
		$model = self::find($id);
		if (!isset($model))
			return null;
		return $model;
	}

	public function remove($ip){
		$this->saveLog($ip,'delete',[]);
		$this->delete();
	}
}
