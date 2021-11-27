<?php

namespace App\Http\Requests\Web\Lead;

use App\Rules\HasDigits;

class ContatoRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['documento'] = ['required', new HasDigits(11, 14)];
        $rules['mensagem'] = ['required'];
        $rules['assunto_id'] = ['required', 'exists:assuntos,id'];
        return $rules;
    }

    public function messages()
	{
		return
			[
				'mensagem.required' => 'Informe a mensagem.',
                'assunto_id.required' => 'Campo obrigatório.'
			];
    }
}
