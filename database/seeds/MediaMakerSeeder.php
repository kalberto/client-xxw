<?php

use App\Model\MediaResize;
use App\Model\MediaRoot;
use Illuminate\Database\Seeder;

class MediaMakerSeeder extends Seeder
{

	private function getResize($w,$h,$p,$a){
		return ['width'=>$w,'height'=>$h,'path'=>$p,'action'=>$a];
	}

	private function getSize($w,$h){
		return ['width'=>$w,'height'=>$h];
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mrs = [
	        ['alias' => 'medias','path'=> 'upload/media/'],
			['alias' => 'medias_conteudo','path'=> 'upload/conteudo-evento/interna/','resizes' => [$this->getResize(326,196,'small/','resize')]],
        ];
        $mrt = ['width'=>150,'height'=>150,'path'=>'thumb/','action'=>'resize'];
        foreach ($mrs as $mr){
        	if(MediaRoot::where('alias','=',$mr['alias'])->count()){
		        $mediaRoot = MediaRoot::where('alias','=',$mr['alias'])->first();
		        $mediaRoot->alias = $mr['alias'];
		        $mediaRoot->path = $mr['path'];
		        $mediaRoot->save();
	        }else{
		        $mediaRoot = new MediaRoot();
		        $mediaRoot->alias = $mr['alias'];
		        $mediaRoot->path = $mr['path'];
		        $mediaRoot->save();
	        }
	        if(MediaResize::where([['path','=','thumb/'],['media_root_id','=',$mediaRoot->id]])->count() == 0){
		        $mediaResize = new MediaResize();
		        $mediaResize->fill($mrt);
		        $mediaResize->media_root_id = $mediaRoot->id;
		        $mediaResize->save();
	        }
	        if(isset($mr['resizes'])){
		        foreach ($mr['resizes'] as $resize){
			        if(MediaResize::where([['path','=',$resize['path']],['media_root_id','=',$mediaRoot->id]])->count() == 0){
				        $mediaResize = new MediaResize();
				        $mediaResize->fill($resize);
				        $mediaResize->media_root_id = $mediaRoot->id;
				        $mediaResize->save();
			        }
		        }
	        }
        }
    }
}
