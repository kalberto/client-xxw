<?php

namespace App\Http\Requests\Admin\Faq;

use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CreateRequest extends Request
{
	protected static $permission = 2;

}
