
<div class="row">
	<div class="col-sm-3">
		<label class="form-control-label text-xs-right" for="perfil_id"> Perfis: </label>
		<select id="perfil_id" class="c-select form-control" name="perfil_id" @change="syncPerfil" v-model="currentLista.perfil_id" required>
			<option value="" disabled>Selecione um perfil</option>
			<option v-for="perfil in perfis" :value="perfil.id">@{{ perfil.nome }}</option>
		</select>
	</div>
    @include('admin.include.forms.input-select-multiple',['input_size' => 9, 'clear' => 'false', 'input' => '"niveis_id"','input_label' => 'Niveis', 'vue_var' => 'currentLista.niveis_id','options' => 'niveis','select_value' => 'nivel => nivel.id','select_text' => 'nome'])
    {{-- <div class="col-sm-9">
        <label class="control-label" :for="'niveis_id'">Níveis</label>
        <v-select :id="'niveis_id'" :clearable="true" :name="'niveis_id'" v-model="currentLista.niveis_id" :options="niveis" multiple label="nome" :reduce="nivel => nivel.id"  style="position:relative;" @input="focusVSelect('niveis_id')" placeholder="Selecione" ></v-select>
        <input :id="'niveis_id'" :name="'niveis_id'" :ref="'niveis_id'" hidden>
    </div> --}}
</div>
<div class="subtitle-block"></div>
<div class="row form-group" v-if="currentListaVisible">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-12">
				<div class="col-sm-6">
					<div class="row">
						<div class="col-m-3" v-if="!lista_being_created">
							<button class="btn btn-success text-white" type="button" v-on:click="addLista">Atualizar Lista</button>
						</div>
						<div class="col-m-3" v-else>
							<button class="btn btn-success text-white" type="button" v-on:click="addLista">Adicionar Lista</button>
						</div>
						<div class="col-sm-3">
							<button class="btn btn-secondary" type="button" v-on:click="stopAddLista">Cancelar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

