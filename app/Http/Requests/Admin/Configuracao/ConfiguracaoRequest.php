<?php

namespace App\Http\Requests\Admin\Configuracao;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ConfiguracaoRequest extends FormRequest {

	public function rules(){
		return [
			'nome_app' => 'required|min:3|max:45',
			'seo_sufix' => 'max:45',
			'tag_manager_id' => 'max:15',
			'email_remetente' => 'max:145|nullable|email',
			'email_destinatario' => 'max:145|nullable|email',
		];
	}

	public function messages(){
		return
			[
				'nome_app.required' => 'O nome é obrigatório',
				'nome_app.max' => 'O nome não pode passar de :max caracteres',
				'nome_app.min' => 'O nome deve ter no mínimo :min caracteres',
				'seo_sufix.max' => 'O seo sufix não pode passar de :max caracteres',
				'tag_manager_id.max' => 'A tag manager não pode passar de :max caracteres',
				'email_remetente.max' => 'O email remetente não pode passar de :max caracteres',
				'email_remetente.email' => 'E-mail no formato inválido',
				'email_destinatario.max' => 'O email destinatário não pode passar de :max caracteres',
				'email_destinatario.email' => 'E-mail no formato inválido',
			];
	}

	public function validar($data){
		return Validator::make($data,$this->rules(),$this->messages());
	}

}
