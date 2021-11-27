<?php

namespace App\Http\Requests\Web\Usuario;

use App\Http\Requests\Web\Request as BaseRequest;
use App\Rules\DocumentoValid;
use App\Rules\HasDigits;
use App\Helpers\VariableHelper;
use App\Rules\SameDocumento;
use Illuminate\Support\Facades\DB;

class PreCadastroRequest extends BaseRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'documento' => ['required','min:2','max:150',new DocumentoValid()],
			'razao_social' => 'required|min:2|max:130',
			'contato_responsavel' => ['required','min:2','max:100'],
			'telefone' => ['required', new HasDigits(10, 16)],
			'email' => ['required', 'max:190', 'email'],
			'estado_id' => ['required','exists:estados,id'],
			'cidade_id' => ['required','exists:cidades,id'],
		];
	}

	public function messages()
	{
		return
			[
				'documento.required' => 'O documento é obrigatório.',
				'documento.size' => 'O documento deve conter :size',
				'documento.unique' => 'documento já cadastrado',
				'razao_social.required' => 'O campo é obrigatório.',
				'razao_social.min' => 'O campo deve conter no mínimo :min caracteres.',
				'razao_social.max' => 'O campo deve conter no máximo :max caracteres.',
				'contato_responsavel.required' => 'O campo é obrigatório.',
				'contato_responsavel.min' => 'O campo deve conter no mínimo :min caracteres.',
				'contato_responsavel.max' => 'O campo deve conter no máximo :max caracteres.',
				'telefone.required' => 'O campo é obrigatório.',
				'telefone.max' => 'O campo deve conter no máximo :max caracteres.',
				'email.required' => 'O campo é obrigatório.',
				'email.max' => 'O campo deve conter no máximo :max',
				'email.email' => 'O campo deve ser um e-mail',
				'estado_id.required' => 'Selecione um estado',
				'estado_id.exists' => 'Selecione um estado válido',
				'cidade_id.required' => 'Selecione uma cidade',
				'cidade_id.exists' => 'Selecione uma cidade válida',
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
