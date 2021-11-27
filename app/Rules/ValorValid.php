<?php

namespace App\Rules;

use function GuzzleHttp\Psr7\str;
use Illuminate\Contracts\Validation\Rule;

class ValorValid implements Rule
{

	private $message = "Valor inválido.";

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		if(!isset($value))
			return false;
		$value = preg_replace("/[^0-9,-]/",'',$value);
		$size_string = strlen($value);
		$pos_v = strpos($value,',');
		if(!($pos_v == $size_string - 3))
			return false;
		if(strpos($value, '-') !== false){
			$this->message = "Não é permitido valores negativos.";
			return false;
		}
		if($value === "0,00"){
			$this->message = "O valor precisa ser maior que 0";
			return false;
		}
		if(strlen($value) > 9){
			$this->message = "O valor máximo é R$ 999.999,00";
			return false;
		}
		return true;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return $this->message;
	}

	/**
	 * Convert the rule to a validation string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return "valorvalid";
	}
}
