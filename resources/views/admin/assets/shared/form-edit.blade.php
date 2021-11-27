<div class="row">
	@include('admin.include.forms.input-select',['input_size' => 3,'input' =>'"tipo"', 'input_label' => 'Selecione um Tipo','vue_var' => 'registro.tipo','v_for' => 'tipo in tipos', 'select_value' => 'tipo.id','select_text' => 'tipo.nome', 'disable' => true])
    @include('admin.include.forms.input-text',['input_size'=> 3,'input'=>'"data_ordenacao"','input_label'=>'Data de Ordenação:','type' => 'date', 'place_holder'=>'01/01/1990','vue_var'=>'registro.data_ordenacao'])
</div>
<div class="row" v-if="registro.tipo == 1">
    @include('admin.include.input-checkbox',['input_size' => 2,'input' => 'thumb','vue_var' => 'registro.thumb','input_label' => 'Thumb Listagem?'])
</div>
<div class="row" v-if="registro.tipo != 2">
    @include('admin.include.forms.input-text',['input_size'=> 4,'input'=>'"legenda"','input_label'=>'Legenda:', 'place_holder'=>'Legenda','vue_var'=>'registro.legenda'])
</div>
@include('admin.assets.shared.form-media')
<div class="form-group" v-if="registro.tipo == 1">
    <label class="col-sm-2 form-control-label text-xs-right"> Imagem atual </label>
    <div class="form-group row">
        <div class="col-sm-10">
            <img class="responsive-img" :src="base_url + '/' + registro.media_root.path + 'thumb/' + registro.file">
        </div>
    </div>
</div>
<div class="form-group" v-if="registro.tipo == 2 && registro.video_is_link != 1">
    <label class="col-sm-2 form-control-label text-xs-right"> Vídeo atual </label>
    <div class="form-group row">
        <div class="col-sm-10">
            <video width="40%" class="responsive-video" controls="">
                <source :src="base_url + '/' + registro.media_root.path + registro.file">
            </video>
        </div>
    </div>
</div>