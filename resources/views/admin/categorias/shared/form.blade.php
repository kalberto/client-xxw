<div class="row">
	@include('admin.include.forms.input-text',['input_size' => 12, 'input' => '"nome"','input_label' => 'Nome:','place_holder' => 'Nome categoria', 'vue_var' => 'registro.nome'])
	@include('admin.include.forms.input-text',['input_size'=>12,'input'=>'"slug"','input_label'=>'Slug:', 'place_holder'=>'Slug','vue_var'=>'registro.slug','blur' => "checkSlug()"])
</div>
