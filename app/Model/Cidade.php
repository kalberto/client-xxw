<?php

namespace App\Model;

use App\Model\Estado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cidade extends Authenticatable
{
    use Notifiable;
    //use SoftDeletes;

    protected $table = 'cidades';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'capital', 'seo_slug', 'estado_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estado()
    {
        return $this->belongsTo('App\Model\Estado');
    }

    public function get()
    {
        return $this->with(['estado']);
    }

    public static function findBySlug($slug)
    {
        $representantes = Cidade::where('seo_slug', 'like', $slug)->get();
        if ($representantes->count() > 0) {
            return $representantes->first();
        } else {
            return false;
        }
    }

    public static function findByEstadoAndSlug($id, $slug)
    {
        $cidade = Cidade::where([['estado_id', '=', $id], ['seo_slug', 'like', $slug]])->get();
        if ($cidade->count() > 0) {
            return $cidade->first();
        } else {
            return false;
        }
    }
}