<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExcelRule implements Rule
{

	public function passes($attribute, $value)
	{
		$extension = strtolower($value->getClientOriginalExtension());

		return in_array($extension, ['pdf','csv', 'xls', 'xlsx','ppsx']);
	}

	public function message()
	{
		return 'O arquivo deve ser pdf, csv, xls, xlsx ou ppsx.';
	}
}
