<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 31/03/2021
 */

namespace App\Imports;

use App\Helpers\VariableHelper;
use App\Model\AlunoDiploma;
use App\Model\Canais\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsuariosImport implements ToModel, WithHeadingRow {

	use Importable;

	/**
	 * @param array $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function model(array $row)
	{

		$cnpj = preg_replace("/[^0-9]/", "",$row['cnpj']);
		if(Usuario::find($cnpj))
			return null;
		if($row['uf'] == '-' && $row['cidade'] == '-'){
			$cidade_id = null;
		}else{
			$cidade_id = DB::table('estados')->join('cidades','estados.id','=','cidades.estado_id')
			               ->where([['uf','=',mb_strtoupper($row['uf'],"UTF-8")],['seo_slug','=',Str::slug($row['cidade'])]])
			               ->pluck('cidades.id')->first();
		}
		$nivel_id = DB::table('perfis_usuarios')->join('niveis','perfis_usuarios.id','=','niveis.perfil_id')
		           ->where([['perfis_usuarios.nome','like',$row['perfil']],['niveis.nome','=',$row['nivel']]])
		           ->pluck('niveis.id')->first();
		$data = [
			'documento' => $cnpj,
			'password' => $cnpj,
			'nome_fantasia' => $row['nome_fantasia'],
			'razao_social' => $row['razao_social'],
			'nome' => $row['grupo'],
			'cidade_id' => $cidade_id,
			'nivel_id' => $nivel_id,
			'ativo' => $row['nivel'] !== 'Fora'
		];
		return new Usuario($data);
	}
}
