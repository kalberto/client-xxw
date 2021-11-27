<?php

namespace App\Http\Requests\Web\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RedefinePassRequest extends FormRequest
{

	public function rules()
	{
		return [
			'password' => 'required',
			'new_password' => ['required','min:6','max:12','regex:/^.*(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[@!*$#%.]).*$/i'],
			're_new_password' => 'required|same:new_password'
		];
	}

	public function messages()
	{
		return [
			'password.required' => 'O campo é obrigatório',
			'new_password.required' => 'O campo é obrigatório',
			'new_password.min' => 'A nova senha deve possuir no mínimo :min caracteres',
			'new_password.max' => 'A nova senha deve possuir no máximo :max caracteres',
			'new_password.regex' => 'Deve conter: Maiúscula, minúscula, número e caracter especial(@!*$#%.)',
			're_new_password.required' => 'O campo é obrigatório',
			're_new_password.same' => 'As senhas devem ser iguais',
		];
	}
}
