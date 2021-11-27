<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\AdminModelLog;
use Illuminate\Support\Facades\DB;
use App\Helpers\MailHelper;
use Illuminate\Support\Carbon;

class Evento extends Conteudo
{

    protected $appends = ['perfil_id','medias_relacionadas','conteudos_relacionados'];
    
    public function getPerfilIdAttribute()
    {
        $item = ListaRsvp::with('perfis')->pluck('id');
        if(isset($item) && count($item) > 0)
            return $item[0];
        return null;
    }

    public function getPerfisIdAttribute()
    {
        $ids = DB::table('listas_rsvp')->join('listas_rsvp_perfis','listas_rsvp.id','=','listas_rsvp_perfis.lista_rsvp_id')
                                    ->where('listas_rsvp.conteudo_id','=',$this->id)
                                    ->pluck('listas_rsvp_perfis.perfil_id')->toArray();
        return $ids;
    }

    public function getNiveisIdAttribute()
    {
        $ids = DB::table('listas_rsvp')->join('listas_rsvp_niveis','listas_rsvp.id','=','listas_rsvp_niveis.lista_rsvp_id')
                                ->where('listas_rsvp.conteudo_id','=',$this->id)
                                ->pluck('listas_rsvp_niveis.nivel_id')->toArray();
        return $ids;
    }

    public function getMediasRelacionadasAttribute()
    {
        $itens = $this->media()->pluck('id')->toArray();
        return $itens;
    }

    public function listasRsvp(){
		return $this->hasMany('App\Model\ListaRsvp', 'conteudo_id');
	}

    public function get(){
        return $this->where('evento',1)->with(['categoria']);
	}

    public static function store($data, $ip){
        $registro = parent::store($data, $ip);
        $registro  = Evento::find($registro['id']);
        $lista = $registro->listasRsvp()->save(new ListaRsvp());
        $lista->perfis()->sync($data['perfis_id']);
        $lista->niveis()->sync($data['niveis_id']);
        if(!isset($data['niveis_id']) || count($data['niveis_id']) <= 0){
            $usuarios_documentos = DB::table('usuarios')->join('niveis','usuarios.nivel_id','=','niveis.id')
                                                ->whereIn('perfil_id',$data['perfis_id'])->pluck('usuarios.documento')
                                                ->toArray();
            $lista->convidados()->sync($usuarios_documentos);                       
        }else{
            $niveis = $lista->niveis()->get();
            foreach($niveis as $nivel){
                $usuarios = DB::table('usuarios')->where('nivel_id',$nivel->id)->select('documento')->get();
                foreach($usuarios as $usuario){
                    DB::table('convidados_listas_rsvp')->updateOrInsert(['lista_rsvp_id' => $lista->id,'documento' => $usuario->documento]);
                }
            }
        }
	    if(!empty($data['conteudos_relacionados']))
		    $registro->conteudosRelacionados()->sync($data['conteudos_relacionados']);
        return true;
    }

    public static function edit($id, $data, $ip){
        $registro = parent::edit($id, $data, $ip);
        $registro  = Evento::find($registro['id']);
        $lista = $registro->listasRsvp()->first();
        if(isset($lista)){
            $lista->perfis()->sync([]);
            $lista->niveis()->sync([]);
            $lista->perfis()->sync($data['perfis_id']);
            $lista->niveis()->sync($data['niveis_id']);
        }
        $documentos_db = DB::table('convidados_listas_rsvp')->where([['lista_rsvp_id','=',$lista->id],['confirmado','=',false]])
                                    ->pluck('documento')->toArray();
        if(!isset($data['niveis_id']) || count($data['niveis_id']) <= 0){
            $documentos_request = DB::table('usuarios')->join('niveis','usuarios.nivel_id','=','niveis.id')
                                                ->whereIn('perfil_id',$data['perfis_id'])->pluck('usuarios.documento')
                                                ->toArray();                    
        }else{
            $documentos_request = DB::table('usuarios')->whereIn('nivel_id',$data['niveis_id'])->pluck('documento')->toArray();
        }
        $to_be_removed = array_values(array_diff($documentos_db, $documentos_request));
        if(count($to_be_removed) > 0)
            DB::table('convidados_listas_rsvp')->whereIn('documento',$to_be_removed)->where('lista_rsvp_id','=',$lista->id)->delete();
        if(count($documentos_request)> 0){
            $to_be_add = array_values(array_diff($documentos_request, $documentos_db));
            $lista->convidados()->syncWithoutDetaching($to_be_add);
        }
	    if(!empty($data['conteudos_relacionados']))
		    $registro->conteudosRelacionados()->sync($data['conteudos_relacionados']);
        return true;
    }
}
