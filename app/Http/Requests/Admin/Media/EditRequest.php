<?php

namespace App\Http\Requests\Admin\Media;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;


class EditRequest extends FormRequest {


	public function rules(){
		return [
			'nome' => 'min:3|max:50',
			'legenda' => 'max:600',
			'file' => 'mimes:mp4,jpg,png,jpeg|min:5',
			'alias' => 'required',
		];
	}

	public function messages()
	{
		return
			[
				'nome.required' => 'Nome obrigatório',
				'nome.max' => 'O nome não pode passar de 255 caracteres',
				'nome.min' => 'O nome deve ter no mínimo 3 caracteres',
				'legenda.max' => 'A legenda não pode ter mais de 255 caracteres',
				'file.mimes' => 'O arquivo deve ser extensão mp4, jpg, png ou jpeg.',
				'file.max' => 'O arquivo deve ter no máximo :max kbs.',
				'file.min' => 'O arquivo deve ter no mínimo 5kbs.'
			];
	}


	public function validar($data)
	{
		$v = Validator::make($data,$this->rules(), $this->messages());
		$v->sometimes('file','max:2000',function ($input){
			return $input->tipo == 1;
		});
		$v->sometimes('file','max:300000',function ($input){
			return $input->tipo == 2;
		});
		return $v;
	}
}
