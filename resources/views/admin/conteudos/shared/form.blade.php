<div class="row">
	<div class="form-group col-sm-2">
		<label class="control-label" :for="'perfil_id'">Perfil</label>
		<select :id="'perfil_id'" :name="'perfil_id'" :ref="'perfil_id'" class="c-select form-control" v-model="registro.perfil_id">
			<option value="">Ambos</option>
			<option v-for="perfil in perfis" :value="perfil.id">@{{perfil.nome}}</option>
		</select>
	</div>
	@include('admin.include.forms.input-checkbox',['input_size'=>6,'input' => '"ativo"', 'vue_var' => 'registro.ativo','input_label'=>'Ativo'])
</div>
<div class="row">
    @include('admin.include.forms.input-text',['input_size'=>4,'input'=>'"nome"','input_label'=>'Nome:', 'place_holder'=>'Nome','vue_var'=>'registro.nome'])
	@include('admin.include.forms.input-text',['input_size'=>4,'input'=>'"titulo"','input_label'=>'Título:', 'place_holder'=>'Título','vue_var'=>'registro.titulo','blur' => "updateSlug()"])
    @include('admin.include.forms.input-text',['input_size'=>4,'input'=>'"slug"','input_label'=>'Slug:', 'place_holder'=>'Slug','vue_var'=>'registro.slug','blur' => "checkSlug()"])
</div>
<div class="row">
	@include('admin.include.forms.input-text',['input_size'=> 3,'input'=>'"data_inicio"','input_label'=>'Data Publicação:','type' => 'date', 'place_holder'=>'01/01/1990','vue_var'=>'registro.data_inicio'])
	@include('admin.include.forms.input-text',['input_size'=>6,'input'=>'"autor"','input_label'=>'Autor:', 'place_holder'=>'Autor','vue_var'=>'registro.autor'])
</div>
<div class="row">
    @include('admin.include.forms.input-select',['input_size' => 4,'input' =>'"categoria_id"', 'input_label' => 'Categorias','vue_var' => 'registro.categoria_id','v_for' => 'categoria in categorias', 'select_value' => 'categoria.id','select_text' => 'categoria.nome'])
	@include('admin.include.forms.input-select-multiple',['input_size' => 4, 'clear' => 'false', 'input' => '"conteudos_relacionados"','input_label' => 'Eventos e/ou Conteúdos Relacionados', 'vue_var' => 'registro.conteudos_relacionados','options' => 'conteudos_relacionados','select_value' => 'conteudo_relacionado => conteudo_relacionado.id','select_text' => 'nome'])
	@include('admin.include.forms.input-select-multiple-media',['input_size' => 4, 'clear' => 'false', 'input' => '"medias_relacionadas"','input_label' => 'Mídias Relacionadas', 'vue_var' => 'registro.medias_relacionadas','options' => 'medias_relacionadas','select_value' => 'media_relacionada => media_relacionada.id','select_text' => 'nome'])
</div>
<div class="row">
    @include('admin.include.forms.input-trumbowyg',['input_size'=>12,'input'=>"'texto'",'vue_var'=>"registro.texto",'input_label'=>'Texto'])
</div>
<div class="subtitle-block" v-if="registro.id"></div>
<div v-if="registro.id">
	<h5>Documentos</h5>
</div>
<table class="table table-striped table-bordered table-hover">
	<thead>
	<tr>
		<th>Nome</th>
		<th>Download</th>
		<th>Remover</th>
	</tr>
	</thead>
	<tbody>
	<tr v-for="(documento, index) in registro.documentos">
		<td>@{{ documento.nome_original }}</td>
		<td><a target="_blank" :href="base_url + '/upload/conteudo-evento/documentos/' + documento.file">Download</a></td>
		<td><a class="remove" @click="deleteFile(documento.id, index)" v-bind:title="'Deletar ' + documento.nome_original"><i class="fa fa-trash-o "></i></a></td>
	</tr>
	</tbody>
</table>

<div class="row" v-if="registro.id">
	<div class="col-sm-6 form-group">
		<label for="formFileMultiple" class="form-label">Adicionar arquivos</label>
		<input id="formFileMultiple" class="form-control" type="file" ref="documentos" multiple v-on:change="addDocumentos"/>
	</div>
</div>





