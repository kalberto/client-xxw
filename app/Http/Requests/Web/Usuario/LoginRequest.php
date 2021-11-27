<?php

namespace App\Http\Requests\Web\Usuario;

use App\Http\Traits\NumbersOnly;

class LoginRequest extends Request
{

	use NumbersOnly;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'documento' => ['required'],
			'password' => ['required']
		];
	}

	// protected function validationData()
	// {
	// 	$data = $this->all();
	// 	if(isset($data['documento'])) {
	// 		$data['documento'] = $this->getOnlyNumbers($data['documento']);
	// 	}
	// 	return $data;
	// }
}
