<?php

namespace App\Http\Requests\Admin\Faq;

use App\Http\Requests\Admin\Request as BaseRequest;
use Illuminate\Support\Facades\DB;

class Request extends BaseRequest
{

	protected static $modulo = 10;
	protected static $permission = 1;

	public function rules()
	{
		return [
			'pergunta' => 'required|min:5|max:650',
			'resposta' => 'required|min:5|max:1500',
		];
	}

	public function messages()
	{
		return
			[
				'pergunta.required' => 'Campo obrigatório.',
				'pergunta.min' => 'O campo deve conter no mínimo :min caracteres.',
				'pergunta.max' => 'O campo não pode ultrapassar de :max caracteres.',
				'resposta.required' => 'Campo obrigatório.',
				'resposta.min' => 'O campo deve conter no mínimo :min caracteres.',
				'resposta.max' => 'O campo não pode ultrapassar de :max caracteres.'
			];
	}
}
