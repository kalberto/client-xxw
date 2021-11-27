<?php

namespace App\Http\Requests\Web\VPC;

use App\Http\Requests\Web\Request as BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class Request extends BaseRequest
{

	/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [];
	}

	public function messages() {
		return [
			'assunto_vpc_id.required' => 'Campo obrigatório.',
			'dados.nome.required' => 'O campo é obrigatório',
			'dados.nome.max' => 'O campo deve conter no máximo :max',
			'dados.custo.required' => 'O campo é obrigatório',
			'dados.data_inicio.required'=> 'O campo é obrigatório',
			'dados.data_inicio.date_format' => 'O campo precisa ser no formato dd/mm/YYYY',
			'dados.data_inicio.after_or_equal' => 'Nossos especialistas precisam de 30 dias para analisar sua VPC.',
			'dados.data_inicio.before_or_equal' => 'Você não pode pedir uma VPC com mais de 60 dias para ínicio.',
			'dados.data_fim.required'=> 'O campo é obrigatório',
			'dados.data_fim.date_format' => 'O campo precisa ser no formato dd/mm/YYYY',
			'dados.data_fim.after_or_equal' => 'O fim da ação precisa ser depois ou no mesmo dia que o ínicio.',
			'dados.objetivo.required' => 'O campo é obrigatório',
			'dados.publico_alvo.required' => 'O campo é obrigatório',
			'dados.descricao.required' => 'O campo é obrigatório',
			'dados.modelos.required' => 'O campo é obrigatório',
			'anexos.required' => 'O campo é obrigatório',
		];
	}

	protected function failedValidation(Validator $validator)
	{
		$validationException = new ValidationException($validator);
		$errors = $validationException->errors();
		$erros_anexo = $validator->errors()->get('anexos.*');
		$erros_comprovantes = $validator->errors()->get('comprovantes.*');
		if(sizeof($erros_anexo) > 0){
			if(isset($errors['anexos']))
				$errors['anexos'][] = "Os arquivos precisam ser jpeg, png ou pdf com no max 2mb";
			else
				$errors['anexos'] = ["Os arquivos precisam ser jpeg, png ou pdf com no max 2mb"];
		}
		if(sizeof($erros_comprovantes) > 0){
			if(isset($errors['comprovantes']))
				$errors['comprovantes'][] = "Os arquivos precisam ser jpeg, png ou pdf com no max 2mb";
			else
				$errors['comprovantes'] = ["Os arquivos precisam ser jpeg, png ou pdf com no max 2mb"];
		}
		throw new HttpResponseException(
			response()->json([
				'errors' => $errors
			], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
		);
	}
}
