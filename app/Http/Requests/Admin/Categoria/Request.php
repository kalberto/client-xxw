<?php

namespace App\Http\Requests\Admin\Categoria;

use App\Http\Requests\Admin\Request as BaseRequest;
use Illuminate\Support\Facades\DB;

class Request extends BaseRequest
{

	protected static $modulo = 8;
	protected static $permission = 1;

	public function rules()
	{
		return [
			'nome' => ['required','min:3','max:50'],
			'slug' => ['required','min:3','max:50'],
		];
	}

	public function messages()
	{
		return
			[
				'nome.required' => 'O campo é obrigatório.',
				'nome.min' => 'O campo deve conter no mínimo :max',
				'nome.max' => 'O campo deve conter no máximo :max',
				'nome.unique' => 'Essa categoria já está cadastrada.',
				'slug.required' => 'O campo é obrigatório.',
				'slug.min' => 'O campo deve conter no mínimo :max',
				'slug.max' => 'O campo deve conter no máximo :max',
				'slug.unique' => 'Essa slug já está cadastrada.',
			];
	}
}
