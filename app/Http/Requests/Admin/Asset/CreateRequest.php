<?php

namespace App\Http\Requests\Admin\Asset;

class CreateRequest extends Request
{
	protected static $permission = 2;

	public function rules(){
		$rules = parent::rules();
		return $rules;
	}
}
