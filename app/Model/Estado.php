<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';
    public $timestamps = false;

    public static function getAll()
    {
        return Estado::whereNotNull('id')->get();
    }

    public static function findByUf($uf)
    {
        if (isset($uf)) {
            $estado = Estado::where('uf', 'like', $uf)->get();
            if ($estado->count() > 0) {
                return $estado->first();
            }
        }
        return false;
    }
}
