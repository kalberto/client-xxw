<?php

use Illuminate\Database\Seeder;

class AssuntosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$assuntos = [
			'Dúvidas', 'Elogios','Reclamações'
		];
		foreach ($assuntos as $assunto) {
			if(\App\Model\Canais\Assunto::where('assunto','=',$assunto)->count()){
				$model = \App\Model\Canais\Assunto::where('assunto','=',$assunto)->first();
				$model->assunto = $assunto;
			    $model->save();
			}else{
				$model = new \App\Model\Canais\Assunto();
			    $model->assunto = $assunto;
			    $model->save();
			}
	    }
    }
}
