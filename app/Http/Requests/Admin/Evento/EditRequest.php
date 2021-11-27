<?php

namespace App\Http\Requests\Admin\Evento;

class EditRequest extends Request
{
	protected static $permission = 2;

	public function rules(){
		$rules = parent::rules();
		$rules['id'] = ['required','exists:conteudos,id'];
		return $rules;
	}

	protected function validationData()
	{
		$data = parent::validationData();
		$data['id'] = $this->route('id');
		$this->id = $data['id'];
		return $data;
	}
}
