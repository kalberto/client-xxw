<?php

use Illuminate\Database\Seeder;
use App\Model\Categoria;
use Illuminate\Support\Str;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$categorias = [
			'TREINAMENTO'
		];
		foreach ($categorias as $categoria) {
			if(\App\Model\Categoria::where('slug','=',Str::slug($categoria))->count()){
				$model = \App\Model\Categoria::where('slug','=',Str::slug($categoria))->first();
				$model->nome = $categoria;
                $model->slug = Str::slug($categoria);
			    $model->save();
			}else{
				$model = new \App\Model\Categoria();
			    $model->nome = $categoria;
                $model->slug = Str::slug($categoria);
			    $model->save();
			}
	    }
    }
}
