<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lead extends Model
{
	protected $table = 'leads';
	protected $fillable = ['email','documento','assunto_id','mensagem'];
	protected $hidden = ['id'];

	public function assunto(){
		return $this->belongsTo('App\Model\Assunto');
    }

	public function getAssunto(){
		$assunto = DB::table('leads')->where('id',$this->id)->select('assunto')->first();
		return $assunto;
    }

	/**
	 * @param array $data
	 *
	 * @return boolean
	 */
	public static function store($data) {
		$lead = new self;
		$lead->fill($data);
		return $lead->save();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function get(){
		return $this;
	}

	public static function countTotal(){
		return self::count();
	}
}
