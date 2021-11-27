<div class="row">
    @include('admin.include.input-checkbox',['input_size' => 3,'input' => 'video','vue_var' => 'registro.video','input_label' => 'Possui vídeos?'])
    @include('admin.include.input-checkbox',['input_size' => 3,'input' => 'imagem','vue_var' => 'registro.imagem','input_label' => 'Possui imagens?'])
</div>
<div class="row" v-if="registro.video == true">
    @include('admin.include.input-checkbox',['input_size' => 4,'input' => 'video_is_link','vue_var' => 'registro.video_is_link','input_label' => 'Vídeo tem link?'])
    @include('admin.include.input-checkbox',['input_size' => 4,'input' => 'video_arquivo','vue_var' => 'registro.video_arquivo','input_label' => 'Vídeo tem arquivo?'])
</div>
<div class="row" v-if="registro.video_is_link == true">
    @include('admin.include.forms.input-text',['input_size'=>12,'input'=>'"video_link"','input_label'=>'Vídeo Link:', 'place_holder'=>'Vídeo Link','vue_var'=>'registro.video_link'])
</div>
<div class="row" v-if="registro.video_arquivo == true">
    @include('admin.include.forms.medias-selector',['media_size' => 12,'media_title' => 'Vídeos:','media_var' => 'selectedMediasConteudo','media_selector' => '"medias_conteudo"','input' => '"img_topo_id"', 'limit' => 3])
</div>
<div class="row" v-if="registro.imagem == true">
    @include('admin.include.forms.medias-selector',['media_size' => 12,'media_title' => 'Imagens:','media_var' => 'selectedMediasConteudo','media_selector' => '"medias_conteudo"','input' => '"img_topo_id"', 'limit' => 3])
</div>