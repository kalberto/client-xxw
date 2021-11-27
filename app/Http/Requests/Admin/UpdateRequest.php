<?php

namespace App\Http\Requests\Admin;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UpdateRequest extends FormRequest {

	public function rules(){
		return [
			'nome' => 'required|min:3|max:50'
		];
	}

	public function messages(){
		return
			[
				'nome.required' => 'Nome obrigatório',
				'nome.max' => 'O Nome não pode passar de 50 caracteres',
			];
	}

	public function validar($data){
		return Validator::make($data,$this->rules(),$this->messages());
	}

}
