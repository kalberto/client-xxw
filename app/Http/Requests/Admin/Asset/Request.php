<?php

namespace App\Http\Requests\Admin\Asset;

use App\Http\Requests\Admin\Request as BaseRequest;

class Request extends BaseRequest
{

    protected static $modulo = 4;
	protected static $permission = 1;

	public function rules()
	{
		$rules = [
			'tipo' => 'required',
			'file' => ['required_unless:video_is_link,true','nullable','mimes:mp4,jpg,png,jpeg','min:1','max:300000'],
			'legenda' => 'required_if:tipo,1',
			'data_ordenacao' => 'required',
			'video_link' => ['required_if:video_is_link,true'],
		];
		return $rules;
	}

	public function messages()
	{
		return
			[
				'file.required_unless' => 'O campo é obrigatório.',
				'legenda.required_if' => 'O campo é obrigatório.',
				'file.mimes' => 'O arquivo deve ser extensão mp4, jpg, png ou jpeg.',
				'file.max' => 'O arquivo deve ter no máximo :max kbs.',
				'file.min' => 'O arquivo deve ter no mínimo :min kbs.',
				'tipo.required' => 'Campo obrigatório.',
				'data_ordenacao.required' => 'Campo obrigatório.',
				'video_link.required_if' => 'Campo obrigatório.',
			];
	}

	protected function validationData(){
		$data = $this->all();
		return $data;
	}
}
