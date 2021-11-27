<?php

use Illuminate\Database\Seeder;

class PermissoesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::table('permissoes')->insert([ //1
		    'nome'=>'Visualização'
	    ]);
	    DB::table('permissoes')->insert([ //2
		    'nome'=>'Criação e edição',
		    'required_id'=>1
	    ]);
	    DB::table('permissoes')->insert([ //3
		    'nome'=>'Remoção',
		    'required_id'=>2
	    ]);
    }
}
