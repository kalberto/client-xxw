<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    if(DB::table('estados')->count() > 0)
	    	return;

	    DB::table('estados')->delete();

	    DB::table('estados')->insert([
		    'id' => 1,
		    'nome' => 'Acre',
		    'uf' => 'AC',
		    'slug' => Str::slug('Acre')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 2,
		    'nome' => 'Alagoas',
		    'uf' => 'AL',
		    'slug' => Str::slug('Alagoas')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 3,
		    'nome' => 'Amapá',
		    'uf' => 'AP',
		    'slug' => Str::slug('Amapá')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 4,
		    'nome' => 'Amazonas',
		    'uf' => 'AM',
		    'slug' => Str::slug('Amazonas')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 5,
		    'nome' => 'Bahia',
		    'uf' => 'BA',
		    'slug' => Str::slug('Bahia')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 6,
		    'nome' => 'Ceará',
		    'uf' => 'CE',
		    'slug' => Str::slug('Ceará')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 7,
		    'nome' => 'Distrito Federal',
		    'uf' => 'DF',
		    'slug' => Str::slug('Distrito Federal')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 8,
		    'nome' => 'Espírito Santo',
		    'uf' => 'ES',
		    'slug' => Str::slug('Espírito Santo')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 9,
		    'nome' => 'Goiás',
		    'uf' => 'GO',
		    'slug' => Str::slug('Goiás')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 10,
		    'nome' => 'Maranhão',
		    'uf' => 'MA',
		    'slug' => Str::slug('Maranhão')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 11,
		    'nome' => 'Mato Grosso',
		    'uf' => 'MT',
		    'slug' => Str::slug('Mato Grosso')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 12,
		    'nome' => 'Mato Grosso do Sul',
		    'uf' => 'MS',
		    'slug' => Str::slug('Mato Grosso do Sul')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 13,
		    'nome' => 'Minas Gerais',
		    'uf' => 'MG',
		    'slug' => Str::slug('Minas Gerais')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 14,
		    'nome' => 'Pará',
		    'uf' => 'PA',
		    'slug' => Str::slug('Pará')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 15,
		    'nome' => 'Paraíba',
		    'uf' => 'PB',
		    'slug' => Str::slug('Paraíba')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 16,
		    'nome' => 'Paraná',
		    'uf' => 'PR',
		    'slug' => Str::slug('Paraná')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 17,
		    'nome' => 'Pernambuco',
		    'uf' => 'PE',
		    'slug' => Str::slug('Pernambuco')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 18,
		    'nome' => 'Piauí',
		    'uf' => 'PI',
		    'slug' => Str::slug('Piauí')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 19,
		    'nome' => 'Rio de Janeiro',
		    'uf' => 'RJ',
		    'slug' => Str::slug('Rio de Janeiro')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 20,
		    'nome' => 'Rio Grande do Norte',
		    'uf' => 'RN',
		    'slug' => Str::slug('Rio Grande do Norte')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 21,
		    'nome' => 'Rio Grande do Sul',
		    'uf' => 'RS',
		    'slug' => Str::slug('Rio Grande do Sul')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 22,
		    'nome' => 'Rondônia',
		    'uf' => 'RO',
		    'slug' => Str::slug('Rondônia')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 23,
		    'nome' => 'Roraima',
		    'uf' => 'RR',
		    'slug' => Str::slug('Roraima')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 24,
		    'nome' => 'Santa Catarina',
		    'uf' => 'SC',
		    'slug' => Str::slug('Santa Catarina')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 25,
		    'nome' => 'São Paulo',
		    'uf' => 'SP',
		    'slug' => Str::slug('São Paulo')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 26,
		    'nome' => 'Sergipe',
		    'uf' => 'SE',
		    'slug' => Str::slug('Sergipe')
	    ]);
	    DB::table('estados')->insert([
		    'id' => 27,
		    'nome' => 'Tocantins',
		    'uf' => 'TO',
		    'slug' => Str::slug('Tocantins')
	    ]);
    }
}
