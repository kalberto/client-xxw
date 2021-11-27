<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ChangePassRequest extends FormRequest{

	public function rules(){
		return [
			'token' => 'required',
			'password' => 'required|min:6|max:12',
			're_password' => 'required|same:password'
		];
	}

	public function messages(){
		return
			[
				'token.required' => 'O token é obrigatório',
				'password.required' => 'Nova senha obrigatória',
				'password.min' => 'A nova senha deve possuir no mínimo 6 caracteres',
				'password.max' => 'A nova senha deve possuir no máximo 12 caracteres',
				're_password.required' => 'O campo confirme a nova senha é obrigatório',
				're_password.same' => 'As senhas devem ser iguais',
			];
	}

	public function validar($data){
		return Validator::make($data,$this->rules(),$this->messages());
	}

}