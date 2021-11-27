<?php

namespace App\Helpers;

class NivelHelper {

	public static function isUpgrade($new_nivel,$old_nivel){
		return $new_nivel > $old_nivel;
	}
}
