<?php

namespace App\Http\Requests\Admin\Conteudo;

class EditRequest extends Request
{
	protected static $permission = 2;

	public function rules(){
		$rules = parent::rules();
		$rules['id'] = ['required','exists:conteudos,id'];
		return $rules;
	}

	/**
	 * Get data to be validated from the request.
	 *
	 * @return array
	 */
	protected function validationData()
	{
		$data = parent::validationData();
		$data['id'] = $this->route('id');
		$this->id = $data['id'];
		return $data;
	}
}
