<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 31/03/2021
 */

namespace App\Imports;

use App\Helpers\VariableHelper;
use App\Model\Canais\Usuario;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Model\Nivel;
use App\Model\MetaUsuario;
use App\Helpers\MonthHelper;
use App\Model\ResultadoMes;

class MetasImport implements ToModel, WithHeadingRow {

	use Importable;

	protected $table = 'metas_usuario';

	/**
	 * @param array $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function model(array $row)
	{

		$mes = MonthHelper::check($row['mes']);
		$documento = preg_replace("/[^0-9]/", "",$row['cnpj']);
		if(DB::table('usuarios')->where('documento','=',$documento)->count() == 0)
			return null;
		$meta = DB::table('metas_usuario')
		           ->where([['metas_usuario.documento',$documento],['metas_usuario.ano',$row['ano']],['metas_usuario.mes',$mes]])
		           ->first();
		if(isset($meta)){
			$data = [
				'meta_mes' => VariableHelper::treatMoney($row['valor_meta']),
				'meta_trimestre' => VariableHelper::treatMoney($row['valor_meta_trimestre']),
			];
			$meta_db = MetaUsuario::find($meta->id);
			$meta_db->fill($data);
			$meta_db->save();
		}else{
			$data = [
				'documento' => $documento,
				'ano' => $row['ano'],
				'mes' => $mes,
				'meta_mes' => VariableHelper::treatMoney($row['valor_meta']),
				'meta_trimestre' => VariableHelper::treatMoney($row['valor_meta_trimestre']),
			];
			$meta_db = new MetaUsuario();
			$meta_db->fill($data);
			$meta_db->save();
		}
		$resultado_mes = DB::table('resultados_mes')->where([['documento',$documento],['metas_usuario',$meta_db->id]])->first();
		if(isset($resultado_mes)){
			$data = [
				'valor_mes' => VariableHelper::treatMoney($row['valor']),
				'valor_falta_vpc' => VariableHelper::treatMoney($row['valor_falta_vpc']),
				'valor_falta_rebate' => VariableHelper::treatMoney($row['valor_falta_rebate']),
				'rebate_disponivel' => VariableHelper::treatMoney($row['rebate_disponivel'])
			];
			$registro = ResultadoMes::find($resultado_mes->id);
			$registro->fill($data);
			$registro->save();
		}else{
			$data = [
				'documento' => preg_replace("/[^0-9]/", "",$row['cnpj']),
				'valor_mes' => VariableHelper::treatMoney($row['valor']),
				'valor_falta_vpc' => VariableHelper::treatMoney($row['valor_falta_vpc']),
				'valor_falta_rebate' => VariableHelper::treatMoney($row['valor_falta_rebate']),
				'rebate_disponivel' => VariableHelper::treatMoney($row['rebate_disponivel']),
				'metas_usuario' => $meta_db->id
			];
			return new ResultadoMes($data);
		}
		return $registro;
	}
}
