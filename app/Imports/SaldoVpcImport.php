<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 06/10/2021
 */

namespace App\Imports;

use App\Helpers\VariableHelper;
use App\Model\Canais\Usuario;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Helpers\MonthHelper;
use Maatwebsite\Excel\Row;

class SaldoVpcImport implements OnEachRow, WithHeadingRow {

	use Importable;

	protected $erros = null;

	public function __construct(&$errors) {
		$this->erros = &$errors;
	}

	public function onRow(Row $row)
	{
		$key = $row->getIndex() + 2;
		$row      = $row->toArray();
		$documento = preg_replace("/[^0-9]/", "",$row['cnpj']);
		if(DB::table('usuarios')->where('documento','=',$documento)->count() == 0){
			$this->erros[] = 'Linha '.$key.' O CNPJ '.$row['cnpj'].' não cadastrado no banco de dados';
			return null;
		}
		$validade = $row['validade'];
		if(!is_string($validade))
			$validade = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($validade))->format('d/m/Y');
		if(!VariableHelper::convertDateFormat($validade,"d/m/Y","Y-m-d")){
			$this->erros[] = 'Linha '.$key.' A Validade não está no formato adequado (d/m/Y)';
			return null;
		}
		$mes = MonthHelper::check($row['mes']);
		$saldo_vpc = VariableHelper::treatMoney($row['saldo']);
		DB::table('saldo_vpc_usuario')->updateOrInsert([
				'documento' => $documento,'ano' => $row['ano'],'mes' => $mes ],
			['saldo_vpc' => $saldo_vpc,'data_validade' => $validade]);
	}
}
