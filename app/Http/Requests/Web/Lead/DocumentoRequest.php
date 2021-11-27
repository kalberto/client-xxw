<?php

namespace App\Http\Requests\Web\Lead;

use App\Rules\HasDigits;

class DocumentoRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['nome'] = ['required','min:3','max:170'];
        $rules['email'] = ['required','email','min:3','max:200'];
        $rules['telefone'] = ['required', new HasDigits(10, 16)];
        $rules['whatsapp'] = ['required', new HasDigits(10, 16)];
        return $rules;
    }

    public function messages()
	{
		return
			[
				'nome.required' => 'Informe seu nome.',
				'nome.min' => 'Mínimo de :min caracteres.',
                'nome.max' => 'Máximo de :max caracteres.',
				'email.required' => 'Informe seu e-mail.',
				'email.email' => 'Informe um e-mail válido.',
                'email.min' => 'Mínimo de :max caracteres.',
                'email.max' => 'Máximo de :max caracteres.',
			];
    }
}
