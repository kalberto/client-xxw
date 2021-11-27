<div class="row">
	<div class="form-group col-sm-2">
		<label class="control-label" :for="'perfil_id'">Perfil</label>
		<select :id="'perfil_id'" :name="'perfil_id'" :ref="'perfil_id'" class="c-select form-control" v-model="registro.perfil_id">
			<option value="">Ambos</option>
			<option v-for="perfil in perfis" :value="perfil.id">@{{perfil.nome}}</option>
		</select>
	</div>
	@include('admin.include.forms.input-text',['input_size' => 10, 'input' => '"pergunta"','input_label' => 'Pergunta:','place_holder' => 'pergunta ? ', 'vue_var' => 'registro.pergunta'])
</div>
<div class="row">
	@include('admin.include.forms.text-area',['rows' => 10,'input_size' => 12,'input' => '"resposta"','input_label' => 'Resposta:','place_holder' => 'resposta', 'vue_var' => 'registro.resposta'])
</div>
