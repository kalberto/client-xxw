<?php

namespace App\Http\Requests\Admin\Conteudo;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\Request as BaseRequest;

class Request extends BaseRequest
{

	protected static $modulo = 6;
	protected static $permission = 1;
	protected $id = null;

	public function rules()
	{
		return [
			'nome' => ['required', 'max:100'],
			'titulo' => ['required', 'max:50'],
			'slug' => ['required', 'max:50',Rule::unique('conteudos')->where(function($query){return $query->where('deleted_at','=',null);})->ignore($this->id)],
			'data_inicio' => ['required','date_format:Y-m-d'],
			'autor' => ['required', 'max:20'],
			'medias_relacionadas' => ['required','array','between:1,3'],
			'texto' => ['required'],
		];
	}

	public function messages()
	{
		return
			[
				'nome.required' => 'O campo é obrigatório.',
				'nome.max' => 'O campo deve conter no máximo :max',
				'titulo.required' => 'O campo é obrigatório.',
				'titulo.max' => 'O campo deve conter no máximo :max',
				'slug.required' => 'O campo é obrigatório.',
				'slug.max' => 'O campo deve conter no máximo :max',
				'slug.unique' => 'Já possui um evento/conteúdo com essa slug',
				'data_inicio.required' => 'O campo é obrigatório.',
				'data_inicio.date_format' => 'Formato Y-m-d',
				'autor.required' => 'O campo é obrigatório.',
				'autor.max' => 'O campo deve conter no máximo :max',
				'medias_relacionadas.required' => 'Campo obrigatorio',
				'medias_relacionadas.between' => 'Selecione 1,2 ou 3 midias',
				'texto.required' => 'O campo é obrigatório.',
			];
	}

	protected function validationData(){
		$data = $this->all();
		$data['slug'] = isset($data['slug']) ? Str::slug($data['slug']) : null;
		return $data;
	}

}
