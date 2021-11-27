<?php

namespace App\Rules;

use App\Http\Traits\NumbersOnly;
use Illuminate\Contracts\Validation\Rule;

class HasDigits implements Rule
{
	use NumbersOnly;

	private $digits = null;
	private $digits_between = null;

	public function __construct($size,$size_between = false)
	{
		$this->digits = $size;
		$this->digits_between = $size_between;
	}

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
		$size_string = strlen($value);
		if($this->digits_between != false){
			if($size_string >= $this->digits && $size_string <= $this->digits_between)
				return true;
		}
		if($size_string == $this->digits)
			return true;
		return false;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return 'Precisa ter entre '.$this->digits.' e '.$this->digits_between.' números';
	}
}
