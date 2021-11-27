<?php

namespace App\Http\Requests\Web\Lead;

use App\Http\Requests\Web\Request as BaseRequest;

class Request extends BaseRequest
{

	/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'documento' => ['required', 'min:11', 'max:14']
		];
	}

	public function messages() {
		return [
			'nome.required' => 'Informe seu nome.',
			'nome.min' => 'Mínimo de :min caracteres.',
			'nome.max' => 'Máximo de :max caracteres.',
			'documento.required' => 'Informe seu e-mail.',
			'documento.min' => 'Mínimo de :max caracteres.',
			'documento.max' => 'Máximo de :max caracteres.',
		];
	}
}
