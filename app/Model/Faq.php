<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
	protected $table = 'faqs';
	protected $fillable = ['pergunta','resposta'];
	protected $hidden = ['id','created_at','updated_at'];

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
