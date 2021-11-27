<?php

namespace App\Helpers;

class VariableHelper {
	public static function convert_string_bool(&$value){
		if($value === "true")
			$value = true;
		elseif($value === "1")
			$value =  true;
		elseif($value === true)
			$value = true;
		elseif($value === 1)
			$value = true;
		else
			$value = false;
	}

	public static function convertDateFormat(&$date, $old_format, $new_format){
		if(isset($date)){
			$data = \DateTime::createFromFormat( $old_format,$date);
			if($data != false)
				$date = $data->format($new_format);
			else
				return false;
		}
		return true;
	}

	public static function treatMoney($value){
		$value = preg_replace("/[^0-9.]/",'',$value);
		return floatval($value);
	}

	public static function mask($val, $mask) {
		$maskared = '';
		$k = 0;
		for($i = 0; $i<=strlen($mask)-1; $i++) {
			if($mask[$i] == '#') {
				if(isset($val[$k])) $maskared .= $val[$k++];
			} else {
				if(isset($mask[$i])) $maskared .= $mask[$i];
			}
		}
		return $maskared;
	}
}
