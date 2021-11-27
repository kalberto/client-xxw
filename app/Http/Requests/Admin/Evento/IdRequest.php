<?php

namespace App\Http\Requests\Admin\Evento;

use App\Http\Requests\Admin\Request as BaseRequest;

class IdRequest extends BaseRequest
{

    protected static $modulo = 7;
	protected static $permission = 2;

	protected function validationData()
	{
		$data = $this->all();
		$data['id'] = $this->route('id');
		return $data;
	}

	public function rules()
	{
		return [
			'id' => ['required', 'exists:conteudos'],
		];
	}

	public function messages()
	{
		return
			[
				'id.required' => 'O campo é obrigatório.',
				'id.exists' => 'Registro inválido.',
			];
	}
}
