<?php

namespace App\Helpers;

class PaginatorHelper {

	public static function paginate($lastPage,$currentpage){
		if(1 == $lastPage)
			return false;
		else{
			$left = $currentpage - 2;
			$right = $currentpage + 2;
			if($left <= 1){
				$start = 1;
				$right = $start + 4;
			}else {
				$start = $left;
			}
			if(($right + (($right - $start) - 4))  <= $lastPage){
				$end = ($right + (($right - $start) - 4));
			}else{
				$end = $lastPage;
			}
			if($start + 4 > $end){
				$start = $end - 4;
				if($start <= 0)
					$start = 1;
			}
			$pages = [];
			for ($i = $start; $i <= $end; $i++){
				$pages[] = $i;
			}
			return $pages;
		}
	}
}
