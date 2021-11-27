<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class MonthHelper {

	public static function check($value){
		$value = Str::slug($value);
		if($value == "janeiro")
			$value = '01';
		elseif($value == "fevereiro")
			$value = '02';
		elseif($value == "marco")
			$value = '03';
		elseif($value == "abril")
			$value = '04';
		elseif($value == "maio")
			$value = '05';
		elseif($value == "junho")
			$value = '06';
		elseif($value == "julho")
			$value = '07';
		elseif($value == "agosto")
			$value = '08';
		elseif($value == "setembro")
			$value = '09';
		elseif($value == "outubro")
			$value = '10';
		elseif($value == "novembro")
			$value = '11';
		elseif($value == "dezembro")
			$value = '12';
		return intval($value);
	}

	public static function getMonth($value){
		if($value == 1)
			$value = 'Janeiro';
		elseif($value == 2)
			$value = 'Fevereiro';
		elseif($value == 3)
			$value = 'Março';
		elseif($value == 4)
			$value = 'Abril';
		elseif($value == 5)
			$value = 'Maio';
		elseif($value == 6)
			$value = 'Junho';
		elseif($value == 7)
			$value = 'Julho';
		elseif($value == 8)
			$value = 'Agosto';
		elseif($value == 9)
			$value = 'Setembro';
		elseif($value == 10)
			$value = 'Outubro';
		elseif($value == 11)
			$value = 'Novembro';
		elseif($value == 12)
			$value = 'Dezembro';
		return $value;
	}

	public static function getMonthTrimestre($value){
		if($value == 1 || $value == 2 || $value == 3)
			return [1,2,3];
		if($value == 4 || $value == 5 || $value == 6)
			return [4,5,6];
		if($value == 7 || $value == 8 || $value == 9)
			return [7,8,9];
		if($value == 10 || $value == 11 || $value == 12)
			return [10,11,12];
		return [];
	}

	public static function getMonthCurrentTrimestre($value){
		if($value == 1)
			return [1];
		if($value == 2)
			return [1,2];
		if($value == 3)
			return [1,2,3];
		if($value == 4)
			return [4];
		if($value == 5)
			return [4,5];
		if($value == 6)
			return [4,5,6];
		if($value == 7)
			return [7];
		if($value == 8)
			return [7,8];
		if($value == 9)
			return [7,8,9];
		if($value == 10)
			return [10];
		if($value == 11)
			return [10,11];
		if($value == 12)
			return [10,11,12];
		return [];
	}

	public static function getTrimestreMonth($value){
		if($value == 1 || $value == 2 || $value == 3)
			return 'Primeiro';
		if($value == 4 || $value == 5 || $value == 6)
			return 'Segundo';
		if($value == 7 || $value == 8 || $value == 9)
			return 'Terceiro';
		if($value == 10 || $value == 11 || $value == 12)
			return 'Quarto';
		return 0;
	}

	public static function getTrimentreAndMonths($value){
		$trimestres = [];
		$trimestres[] = [
			'nome' => 'Primeiro',
			'array' => [1,2,3]
		];
		$trimestres[] = [
			'nome' => 'Segundo',
			'array' => [4,5,6]
		];
		$trimestres[] = [
			'nome' => 'Terceiro',
			'array' => [7,8,9]
		];
		$trimestres[] = [
				'nome' => 'Quarto',
				'array' => [10,11,12]
			];
		return $trimestres;
	}
}
