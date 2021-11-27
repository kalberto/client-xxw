<?php

namespace App\Http\Requests\Admin\Categoria;

use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class EditRequest extends Request
{
	protected static $permission = 2;
	public function rules(){
		$rules = parent::rules();
		$rules['id'] = ['required'];
		return $rules;
	}

	protected function validationData()
	{
		$data = $this->all();
		$data['id'] = $this->route('id');
		$data['slug'] = isset($data['slug']) ? Str::slug($data['slug']) : null;
		return $data;
	}
}
