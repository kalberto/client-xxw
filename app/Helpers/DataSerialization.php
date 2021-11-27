<?php

namespace App\Helpers;


class DataSerialization {

	public static function removeNullFromArray($array){
		$keys = array_keys($array,NULL);
		foreach ($keys as $key){
			unset($array[$key]);
		}
		return $array;
	}
}