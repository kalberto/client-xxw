<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerfisUsuariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $perfis = [
	    	[
	    		'nome' => 'Distribuidor','niveis' =>
			    [
			    	['nome' => 'Fora', 'desconto' => '0%','vpc' => '0%','rebate' => '0%'],
			    	['nome' => 'Bronze', 'desconto' => '8%','vpc' => '1%','rebate' => '0%'],
			    	['nome' => 'Prata', 'desconto' => '12%','vpc' => '1%','rebate' => '1%'],
			    	['nome' => 'Ouro', 'desconto' => '15%','vpc' => '1,5%','rebate' => '1,5%'],
			    	['nome' => 'Diamante', 'desconto' => '18%','vpc' => '2%','rebate' => '2%'],
			    	['nome' => 'Platina', 'desconto' => '20%%','vpc' => '2,5%','rebate' => '2,5%'],
			    ]
		    ],
		    [
		    	'nome' => 'RNSA','niveis' =>
			    [
				    ['nome' => 'Fora', 'desconto' => '0%','vpc' => '0%','rebate' => '0%'],
				    ['nome' => 'RNSA', 'desconto' => '22%','vpc' => '0%','rebate' => '0,5%'],
				    ['nome' => 'RNSA STAR', 'desconto' => '25%','vpc' => '0%','rebate' => '1,5%']
                ]
		    ]
	    ];
	    foreach ($perfis as $perfil){
	    	$id = DB::table('perfis_usuarios')->where([['nome','=',$perfil['nome']]])->pluck('id')->first();
		    if(!isset($id)){
			    $id = DB::table('perfis_usuarios')->insertGetId([
				    'nome'=>$perfil['nome'],
			    ]);
		    }
		    foreach ($perfil['niveis'] as $nivel){
			    if(DB::table('niveis')->where([['nome','=',$nivel['nome']],['perfil_id','=',$id]])->count() <= 0){
				    DB::table('niveis')->insert([
					    'nome'=>$nivel['nome'],
					    'perfil_id' => $id,
					    'desconto' => $nivel['desconto'],
					    'vpc' => $nivel['vpc'],
					    'rebate' => $nivel['rebate'],
				    ]);
			    }else{
			    	DB::table('niveis')->where([['nome','=',$nivel['nome']],['perfil_id','=',$id]])->update([
					    'desconto' => $nivel['desconto'],
					    'vpc' => $nivel['vpc'],
					    'rebate' => $nivel['rebate'],
				    ]);
			    }
		    }
	    }
    }
}
