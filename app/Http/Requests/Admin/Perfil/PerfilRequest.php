<?php

namespace App\Http\Requests\Admin\Perfil;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class PerfilRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'nome' => 'required|min:3|max:100',
		];
    }

    public function messages()
	{
		return
			[
				'nome.required' => 'Nome é obrigatório',
                'nome.min' => 'O nome deve ter no mínimo 3 caracteres',
                'nome.max' => 'O nome não pode passar de 100 caracteres',
			];
    }
    
    public function validar($data)
	{
		return Validator::make($data,$this->rules(), $this->messages());
	}
}
