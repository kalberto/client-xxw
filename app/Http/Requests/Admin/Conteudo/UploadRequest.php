<?php

namespace App\Http\Requests\Admin\Conteudo;


use App\Rules\ExcelRule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Admin\Request as BaseRequest;

class UploadRequest extends BaseRequest
{

	protected static $modulo = 6;
	protected static $permission = 2;

	public function rules()
	{
		return [
			'id' => 'required','exists:conteudos,id',
			'documentos' => ['array','max:10','nullable'],
			'documentos.*' => ['nullable',new ExcelRule(),'max:20000'],
		];
	}

	public function messages()
	{
		return
			[
				'documentos.max' => 'Máximo de :max arquivos.',
				'documentos.*.mimes' => 'O :attribute deve ser extensão xlsx, pdf, csv ou xlsx.',
				'documentos.*.max' => 'O :attribute deve ter no máximo :max kbs.',
			];
	}

	public function attributes()
	{
		return [
			'documentos.0' => 'documento 1',
			'documentos.1' => 'documento 2',
			'documentos.2' => 'documento 3',
			'documentos.3' => 'documento 4',
			'documentos.4' => 'documento 5',
			'documentos.5' => 'documento 6',
			'documentos.6' => 'documento 7',
			'documentos.7' => 'documento 8',
			'documentos.8' => 'documento 9',
			'documentos.9' => 'documento 10',
		];
	}

	protected function validationData(){
		$data = $this->all();
		$data['id'] = $this->route('id');
		return $data;
	}

	protected function failedValidation(Validator $validator)
	{
		$errors = (new ValidationException($validator))->errors();
		foreach ($errors as $key => $error){
			if (strpos($key, 'documentos.') !== false) {
				if(!isset($errors['documentos']))
					$errors['documentos'] = [];
				$errors['documentos'][] = $error;
			}
		}
		throw new HttpResponseException(
			response()->json(['msg' => 'Preencha os campos corretamente.','error_validate' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
		);
	}
}
