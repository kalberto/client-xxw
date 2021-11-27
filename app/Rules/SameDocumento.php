<?php

namespace App\Rules;

use App\Http\Traits\NumbersOnly;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SameDocumento implements Rule
{
	use NumbersOnly;

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		$value = $this->getOnlyNumbers($value);
		if(Auth::user())
			return $value == Auth::user()->documento;
		return false;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return 'O CNPJ não confere.';
	}
}
