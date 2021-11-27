<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perguntas = [
        ];
        foreach ($perguntas as $pergunta){
        	if(DB::table('faqs')->where('pergunta','like',$pergunta['pergunta'])->count() == 0){
        		\App\Model\Canais\Faq::store($pergunta,false);
        	}
        }
    }
}
