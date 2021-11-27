<?php

namespace App\Http\Requests\Web;

use Illuminate\Support\Facades\Validator;

class ContatoRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'nome' => 'required|min:5|max:170',
			'email' => 'required|max:200|email',
			// 'celular' => 'required|max:15',
		];
    }

    public function messages()
	{
		return
			[
				'nome.required' => 'Informe seu nome',
				'nome.min' => 'Mínimo de :min caracteres.',
                'nome.max' => 'Máximo de :max caracteres.',
				'email.required' => 'Informe seu e-mail',
				'email.email' => 'Informe um e-mail válido',
                'email.max' => 'Máximo de :max caracteres.',
                'celular.required' => 'Campo Obrigatório.',
				'celular.max' => 'Máximo de :max caracteres.'
			];
    }
    
    public function validar($data)
	{
		$validator  = Validator::make($data,$this->rules(), $this->messages());
		return $validator;
	}
}
