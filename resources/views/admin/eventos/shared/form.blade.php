<div class="row">
	@include('admin.include.forms.input-checkbox',['input_size'=>6,'input' => '"ativo"', 'vue_var' => 'registro.ativo','input_label'=>'Ativo'])
</div>
<div class="row">
	@include('admin.include.forms.input-text',['input_size'=>4,'input'=>'"nome"','input_label'=>'Nome:', 'place_holder'=>'Nome','vue_var'=>'registro.nome'])
	@include('admin.include.forms.input-text',['input_size'=>4,'input'=>'"titulo"','input_label'=>'Título:', 'place_holder'=>'Título','vue_var'=>'registro.titulo','blur' => "updateSlug()"])
	@include('admin.include.forms.input-text',['input_size'=>4,'input'=>'"slug"','input_label'=>'Slug:', 'place_holder'=>'Slug','vue_var'=>'registro.slug','blur' => "checkSlug()"])
</div>
<div class="row">
	@include('admin.include.forms.input-text',['input_size'=> 3,'input'=>'"data_inicio"','input_label'=>'Data Início:','type' => 'date', 'place_holder'=>'01/01/1990','vue_var'=>'registro.data_inicio'])
	@include('admin.include.forms.input-text',['input_size'=> 3,'input'=>'"data_fim"','input_label'=>'Data Fim:','type' => 'date', 'place_holder'=>'01/01/1990','vue_var'=>'registro.data_fim'])
	@include('admin.include.forms.input-text',['input_size'=>6,'input'=>'"autor"','input_label'=>'Autor:', 'place_holder'=>'Autor','vue_var'=>'registro.autor'])
</div>
<div class="row">
    @include('admin.include.forms.input-text',['input_size'=>12,'input'=>'"link_google_calendar"','input_label'=>'Link Calendário Google:', 'place_holder'=>'Link Calendário Google','vue_var'=>'registro.link_google_calendar'])
</div>
<div class="row">
    @include('admin.include.forms.input-text',['input_size'=>12,'input'=>'"link_transmissao"','input_label'=>'Link Transmissão:', 'place_holder'=>'Link Transmissão','vue_var'=>'registro.link_transmissao'])
</div>
<div class="row">
    @include('admin.include.forms.input-select',['input_size' => 4,'input' =>'"categoria_id"', 'input_label' => 'Categorias','vue_var' => 'registro.categoria_id','v_for' => 'categoria in categorias', 'select_value' => 'categoria.id','select_text' => 'categoria.nome'])
	@include('admin.include.forms.input-select-multiple',['input_size' => 4, 'clear' => 'false', 'input' => '"conteudos_relacionados"','input_label' => 'Eventos e/ou Conteúdos Relacionados', 'vue_var' => 'registro.conteudos_relacionados','options' => 'conteudos_relacionados','select_value' => 'conteudo => conteudo.id','select_text' => 'nome'])
	@include('admin.include.forms.input-select-multiple-media',['input_size' => 4, 'clear' => 'false', 'input' => '"medias_relacionadas"','input_label' => 'Mídias Relacionadas', 'vue_var' => 'registro.medias_relacionadas','options' => 'medias_relacionadas','select_value' => 'media_relacionada => media_relacionada.id','select_text' => 'nome'])
</div>
<div class="row">
    {{-- @include('admin.include.forms.text-area',['rows' => 10,'input_size' => 12,'input' => '"texto"','input_label' => 'Texto:','place_holder' => 'Texto', 'vue_var' => 'registro.texto']) --}}
	@include('admin.include.forms.input-trumbowyg',['input_size'=>12,'input'=>"'texto'",'vue_var'=>"registro.texto",'input_label'=>'Texto'])
</div>
<div class="row">
    @include('admin.include.forms.input-select-multiple',['input_size' => 5, 'clear' => 'false', 'input' => '"perfis_id"','input_label' => 'Perfis', 'vue_var' => 'registro.perfis_id','options' => 'perfis','select_value' => 'perfil => perfil.id','select_text' => 'nome'])
    @include('admin.include.forms.input-select-multiple',['input_size' => 7, 'clear' => 'false', 'input' => '"niveis_id"','input_label' => 'Niveis', 'vue_var' => 'registro.niveis_id','options' => 'niveis','select_value' => 'nivel => nivel.id','select_text' => 'nome'])
</div>