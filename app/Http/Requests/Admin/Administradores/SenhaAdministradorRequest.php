<?php

namespace App\Http\Requests\Admin\Administradores;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SenhaAdministradorRequest extends FormRequest{

	public function rules(){
		return [
			'password' => 'required|min:6|max:12',
			'new_password' => 'required|min:6|max:12',
			're_password_confirmation' => 'required|same:new_password'
		];
	}

	public function messages(){
		return
			[
				'password.required' => 'A senha é obrigatória',
				'password.min' => 'A Senha deve possuir no mínimo :min caracteres',
				'password.max' => 'A Senha deve possuir no máximo :max caracteres',
				'new_password.required' => 'Nova senha obrigatória',
				'new_password.min' => 'A nova senha deve possuir no mínimo :min caracteres',
				'new_password.max' => 'A nova senha deve possuir no máximo :max caracteres',
				're_password_confirmation.required' => 'O campo confirme a nova senha é obrigatório',
				're_password_confirmation.same' => 'As senhas devem ser iguais',
			];
	}

	public function validar($data){
		return Validator::make($data,$this->rules(),$this->messages());
	}

}