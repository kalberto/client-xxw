<?php

use Illuminate\Database\Seeder;

class ConfiguracaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::table('configuracoes')->insert([
			'nome_app'=>'',
			'email_remetente'=>'falecomselect@xxx.com',
			'email_destinatario'=>'falecomselect@xxx.com',
 	    ]);
    }
}
