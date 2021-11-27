<?php

namespace App\Http\Requests\Web\Usuario;

use App\Http\Requests\Web\Request as BaseRequest;
use App\Rules\HasDigits;
use App\Helpers\VariableHelper;
use App\Rules\SameDocumento;
use Illuminate\Support\Facades\DB;

class Request extends BaseRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'contato_responsavel' => 'required|min:2|max:45',
			'data_nascimento' => ['required','date_format:d/m/Y'],
			'password' => ['required','min:6','max:12','regex:/^.*(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[@!*$#%.]).*$/i'],
			're_password' => 'required|same:password',
			'email' =>  ['required', 'max:190', 'email'],
			're_email' => 'required|same:email',
			'documento' => ['required','min:2','max:150', new SameDocumento()],
			'consentimento_lgpd' => ['required','accepted']
		];
	}

	public function messages()
	{
		return
			[
				'contato_responsavel.required' => 'O campo é obrigatório.',
				'contato_responsavel.min' => 'O campo deve conter no mínimo :min caracteres.',
				'contato_responsavel.max' => 'O campo deve conter no máximo :max caracteres.',
				'telefone.required' => 'O campo é obrigatório.',
				'telefone.max' => 'O campo deve conter no máximo :max caracteres.',
				'data_nascimento.required' => 'O campo é obrigatório.',
				'data_nascimento.date_format' => 'O formato deve ser dd/mm/YYYY',
				'password.required' => 'O campo é obrigatório.',
				'password.min' => 'O campo deve conter no mínimo :min caracteres.',
				'password.max' => 'O campo deve conter no máximo :max caracteres.',
				'password.regex' => 'Deve conter: Maiúscula, minúscula, número e caracter especial (@!*$#%.)',
				're_password.required' => 'O campo é obrigatório.',
				're_password.same' => 'As senhas devem ser iguais.',
				'email.required' => 'O campo é obrigatório.',
				'email.max' => 'O campo deve conter no máximo :max',
				'email.email' => 'O campo deve ser um e-mail',
				're_email.same' => 'Os e-mails devem ser iguais',
				're_email.required' => 'O campo é obrigatório.',
				'documento.required' => 'O documento é obrigatório.',
				'documento.size' => 'O documento deve conter :size',
				'documento.unique' => 'documento já cadastrado',
				'consentimento_lgpd.required' => 'O campo é obrigatório.',
				'consentimento_lgpd.accepted' => 'O termo é obrigatório.'
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
