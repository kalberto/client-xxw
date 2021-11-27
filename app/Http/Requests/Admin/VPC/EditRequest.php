<?php

namespace App\Http\Requests\Admin\VPC;

use Illuminate\Validation\Rule;

class EditRequest extends Request
{
	protected static $permission = 2;


	/**
	 * Get the validation rules that apply to the request.
	 *;
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'id' => ['required', 'exists:vpc,id'],
			'comentarios' => ['required','max:500'],
			'status' => ['required',Rule::in(['APROVADO', 'REPROVADA','REVISÃO','PAGO','COMPROVAÇÃO'])],
		];
		return $rules;
	}

	public function messages() {
		return [
			'comentarios.required' => 'O campo é obrigatório',
			'comentarios.max' => 'O campo deve conter no máximo :max',
		];
	}

	protected function validationData()
	{
		$data = $this->all();
		$data['id'] = $this->route('id');
		return $data;
	}
}
