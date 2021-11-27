<?php

use App\Model\Canais\AssuntoVPC;
use Illuminate\Database\Seeder;

class AssuntosVPCTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assuntos = [
	        ['nome' => 'TREINAMENTO', 'porcentagem' => 100.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','descricao','anexos','comprovantes']],
	        ['nome' => 'MÍDIA DIGITAL', 'porcentagem' => 100.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','descricao','modelos','anexos','comprovantes']],
	        ['nome' => 'BRINDES', 'porcentagem' => 100.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','descricao','modelos','anexos','comprovantes']],
	        ['nome' => 'CAMPANHA DE INCENTIVO', 'porcentagem' => 100.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','descricao','modelos','anexos','comprovantes']],
	        ['nome' => 'UNIFORMES', 'porcentagem' => 50.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','modelos','anexos','comprovantes']],
	        ['nome' => 'MATERIAL GRÁFICO', 'porcentagem' => 100.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','descricao','modelos','anexos','comprovantes']],
	        ['nome' => 'FEIRAS E EVENTOS', 'porcentagem' => 100.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','anexos','comprovantes']],
	        ['nome' => 'ADESIVAGEM DE FROTA', 'porcentagem' => 100.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','anexos','comprovantes']],
	        ['nome' => 'FACHADAS', 'porcentagem' => 100.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','anexos','comprovantes']],
	        ['nome' => 'JORNAL/REVISTA', 'porcentagem' => 100.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','descricao','modelos','anexos','comprovantes']],
	        ['nome' => 'TV/RÁDIO', 'porcentagem' => 100.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','descricao','modelos','anexos','comprovantes']],
	        ['nome' => 'MÍDIA EXTERNA', 'porcentagem' => 100.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','descricao','modelos','anexos','comprovantes']],
	        ['nome' => 'SOB DEMANDA', 'porcentagem' => 100.00, 'campos' => ['nome','objetivo','publico_alvo','custo','data_inicio','data_fim','descricao','comprovantes']],
        ];
        foreach ($assuntos as $assunto){
        	$assunto_db = AssuntoVPC::where('nome','=',$assunto['nome'])->first();
        	if(!isset($assunto_db))
        		$assunto_db = new AssuntoVPC();
        	$assunto_db->fill($assunto);
        	$assunto_db->save();
        }
    }
}
