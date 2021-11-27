<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 06/10/2021
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

class ClassificacaoImport implements ToModel, WithHeadingRow {

	use Importable;

	protected $erros = null;
	protected $niveis = null;
	protected $key = 2;

	public function __construct(&$key,&$errors,&$niveis) {
		$this->key = &$key;
		$this->erros = &$errors;
		$this->niveis = &$niveis;
	}

	/**
	 * @param array $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function model(array $row)
	{
		$nivel_id = DB::table('perfis_usuarios')->join('niveis','perfis_usuarios.id','=','niveis.perfil_id')
		              ->where([['perfis_usuarios.nome','like',$row['perfil']],['niveis.nome','=',$row['nivel_atual']]])
		              ->pluck('niveis.id')->first();
		if(!isset($nivel_id)){
			$this->erros[] = 'Linha '.$this->key .' Perfil/Nível não encontrado';
			return null;
		}
		if(!isset($this->niveis[$nivel_id])){
			$this->niveis[$nivel_id] = [
				'desconto' => ($row['desconto']*100).'%',
				'vpc' => ($row['vpc']*100).'%',
				'rebate' => ($row['rebate']*100).'%'
			];
		}
		$documento = preg_replace("/[^0-9]/", "",$row['cnpj']);
		if(DB::table('usuarios')->where('documento','=',$documento)->count() == 0){
			$this->erros[] = 'Linha '.$this->key.' O CNPJ '.$row['cnpj'].' não cadastrado no banco de dados';
			return null;
		}
		$usuario = Usuario::find($documento);
		if($usuario){
			$usuario->nivel_id = $nivel_id;
			$usuario->desconto = ($row['desconto']*100).'%';
			$usuario->vpc = ($row['vpc']*100).'%';
			$usuario->rebate = ($row['rebate']*100).'%';
			$usuario->save();
		}
		$this->key++;
		return $usuario;
	}
}
