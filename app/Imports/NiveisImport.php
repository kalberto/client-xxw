<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 31/03/2021
 */

namespace App\Imports;

use App\Helpers\VariableHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Model\Nivel;

class NiveisImport implements ToModel, WithHeadingRow {

	use Importable;

	protected $table = 'niveis';

	/**
	 * @param array $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function model(array $row)
	{
		$nivel_id = DB::table('perfis_usuarios')->join('niveis','perfis_usuarios.id','=','niveis.perfil_id')
		           ->where([['perfis_usuarios.nome','like',$row['perfil']],['niveis.nome','=',$row['nivel']]])
		           ->pluck('niveis.id')->first();
		$data = [
			'desconto' => ($row['desconto']*100).'%',
			'vpc' => ($row['vpc']*100).'%',
			'rebate' => ($row['rebate']*100).'%'
		];
		$registro = Nivel::find($nivel_id);
		$registro->fill($data);
		$registro->id = $nivel_id;
		$registro->save();
		return $registro;
	}
}
