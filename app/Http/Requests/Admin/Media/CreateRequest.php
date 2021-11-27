<?php

namespace App\Http\Requests\Admin\Media;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;


class CreateRequest extends FormRequest {


	public function rules(){
		return [
			'file' => 'required|mimes:mp4,jpg,png,jpeg|min:1',
			'alias' => 'required',
			'tipo' => 'required',
			'data_ordenacao' => 'required'
		];
	}

	public function messages()
	{
		return
			[
				'file.required' => 'O arquivo é obrigatório.',
				'file.mimes' => 'O arquivo deve ser extensão mp4, jpg, png ou jpeg.',
				'file.max' => 'O arquivo deve ter no máximo :max kbs.',
				'file.min' => 'O arquivo deve ter no mínimo 5kbs.',
				'tipo.required' => 'Campo obrigatório.',
				'data_ordenacao.required' => 'Campo obrigatório.',
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
