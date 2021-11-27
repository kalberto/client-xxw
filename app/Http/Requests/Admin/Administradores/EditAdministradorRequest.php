<?php
namespace App\Http\Requests\Admin\Administradores;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class EditAdministradorRequest extends FormRequest{

	public function rules(){
		return [
			'nome' => 'required|min:3|max:255',
			'sobrenome' => 'max:45',
			'telefone' => 'required|max:15',
			'perfil_id' => 'numeric',
		];
	}

	public function messages(){
		return
			[
				'nome.required' => 'Nome obrigatório',
				'nome.max' => 'O Nome não pode passar de :max caracteres',
				'nome.min' => 'O Sobrenome deve ter no mínimo :min caracteres',
				'sobrenome.max' => 'O Sobrenome não pode passar de :max caracteres',
				'perfil_id.numeric' => 'Selecione uma das opções',
				'telefone.required' => 'Telefone é obrigatório',
				'telefone.size' => 'O telefone deve conter :size números',
				'telefone.numeric' => 'O telefone deve ter no máximo :max numeros'

			];
	}

	public function validar($data){
		return Validator::make($data,$this->rules(),$this->messages());
	}

}