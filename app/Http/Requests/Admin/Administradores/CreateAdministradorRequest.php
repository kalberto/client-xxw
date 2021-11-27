<?php

namespace App\Http\Requests\Admin\Administradores;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;


class CreateAdministradorRequest extends FormRequest {


	public function rules(){
		return [
			'nome' => 'required|min:3|max:255',
			'sobrenome' => 'max:255',
			'email' => 'required|email|max:255|unique:administradores,email,NULL,id,deleted_at,NULL',
			'perfil_id' => 'numeric',
			'password' => 'required|min:6|max:12',
			'api_token' => 'required|unique:administradores|size:60',
			're_password' => 'required|same:password'
		];
	}

	public function messages()
	{
		return
			[
				'nome.required' => 'Nome obrigatório',
				'nome.max' => 'O Nome não pode passar de :max caracteres',
				'nome.min' => 'O Nome deve ter no mínimo :min caracteres',
				'sobrenome.max' => 'O Sobrenome não pode passar de :max caracteres',
				'perfil_id.numeric' => 'Selecione uma das opções',
				'email.required' => 'E-mail obrigatório',
				'email.email' => 'E-mail no formato inválido',
				'email.unique' => 'Este e-mail já está cadastrado',
				'password.required' => 'Senha obrigatória',
				'password.max' => 'A Senha deve possuir no máximo :max caracteres',
				'password.min' => 'A Senha deve possuir no mínimo :min caracteres',
				're_password.required' => 'Confirme sua senha',
				're_password.same' => 'As senhas devem ser iguais',
			];
	}


	public function validar($data)
	{
		return Validator::make($data,$this->rules(), $this->messages());
	}
}
