<?php

namespace App\Http\Requests\Admin\Usuario;

use App\Http\Traits\NumbersOnly;
use App\Rules\DocumentoValid;
use App\Rules\HasDigits;

class UsuarioRequest extends Request
{
	use NumbersOnly;

	protected static $permission = 2;

	/**
	 * Get the validation rules that apply to the request.
	 *;
	 * @return array
	 */
	public function rules()
	{
		return [
			'documento' => ['required', new DocumentoValid(), new HasDigits(11, 14), 'unique:usuarios,documento,NULL,documento,deleted_at,NULL'],
			'perfil_id' => ['required','exists:perfis,id'],
			'nivel_id' => ['required','exists:niveis,id'],
			'razao_social' => 'required|min:2|max:130',
			'nome_fantasia' => 'required|min:2|max:130',
			'nome' => 'required|min:2|max:130',
			'estado_id' => ['required','exists:estados,id'],
			'cidade_id' => ['required','exists:cidades,id'],

			'contato_responsavel' => ['min:2','max:100','nullable'],
			'email' => ['nullable', 'max:190', 'email'],
			'telefone' => ['nullable', new HasDigits(10, 16)],
		];
	}

	public function messages()
	{
		return
			[
				'documento.required' => 'O documento é obrigatório.',
				'documento.size' => 'O documento deve conter :size',
				'documento.unique' => 'Documento já cadastrado',
				'perfil_id.required' => 'Selecione um perfil',
				'perfil_id.exists' => 'Selecione um perfil válid',
				'nivel_id.required' => 'Selecione um nível',
				'nivel_id.exists' => 'Selecione um nível válido',
				'razao_social.required' => 'O campo é obrigatório.',
				'razao_social.min' => 'O campo deve conter no mínimo :min caracteres.',
				'razao_social.max' => 'O campo deve conter no máximo :max caracteres.',
				'nome_fantasia.required' => 'O campo é obrigatório.',
				'nome_fantasia.min' => 'O campo deve conter no mínimo :min caracteres.',
				'nome_fantasia.max' => 'O campo deve conter no máximo :max caracteres.',
				'nome.required' => 'O campo é obrigatório.',
				'nome.min' => 'O campo deve conter no mínimo :min caracteres.',
				'nome.max' => 'O campo deve conter no máximo :max caracteres.',
				'estado_id.required' => 'Selecione um estado',
				'estado_id.exists' => 'Selecione um estado válido',
				'cidade_id.required' => 'Selecione uma cidade',
				'cidade_id.exists' => 'Selecione uma cidade válida',
				'email.required' => 'O campo é obrigatório.',
				'email.max' => 'O campo deve conter no máximo :max',
				'email.email' => 'O campo deve ser um e-mail',
				'email.unique' => 'E-mail já cadastrado',
				'telefone.required' => 'O campo é obrigatório.',
				'telefone.max' => 'O campo deve conter no máximo :max caracteres.',
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
		if(isset($data['documento']))
			$data['documento'] = $this->getOnlyNumbers($data['documento']);
		return $data;
	}
}
