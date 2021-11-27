<?php

namespace App\Http\Requests\Web\Usuario;

use App\Http\Requests\Web\Request as BaseRequest;

class PerfilRequest extends BaseRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'contato_responsavel' => 'nullable|min:2|max:45',
			'data_nascimento' => ['nullable','date_format:d/m/Y'],
			'email' =>  ['nullable','max:190', 'email'],
			'celular' => ['nullable','max:15','min:10']
		];
	}

	public function messages()
	{
		return
			[
				'contato_responsavel.min' => 'O campo deve conter no mínimo :min caracteres.',
				'contato_responsavel.max' => 'O campo deve conter no máximo :max caracteres.',
				'data_nascimento.date_format' => 'O formato deve ser dd/mm/YYYY',
				'email.max' => 'O campo deve conter no máximo :max',
				'email.email' => 'O campo deve ser um e-mail',
				'celular.min' => 'O campo deve conter no mínimo :min caracteres.',
				'celular.max' => 'O campo deve conter no máximo :max caracteres.',
			];
	}

	/**
	 * Get data to be validated from the request.
	 *
	 * @return array
	 */
	protected function validationData()
	{
		$data = $this->all();
		return $data;
	}
}
