<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class Request extends FormRequest
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
        return [
            //
        ];
    }

    /**
	 * Handle a failed validation attempt.
	 *
	 * @param \Illuminate\Contracts\Validation\Validator|Validator $validator
	 *
	 * @return void
	 */
	protected function failedValidation(Validator $validator)
	{
        $validationException = new ValidationException($validator);
		throw new HttpResponseException(
			response()->json([
                'errors' => $validationException->errors()
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
		);
	}
}
