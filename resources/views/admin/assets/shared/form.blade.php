<div class="row">
	@include('admin.include.forms.input-select',['input_size' => 3,'input' =>'"tipo"', 'input_label' => 'Selecione um Tipo','vue_var' => 'registro.tipo','v_for' => 'tipo in tipos', 'select_value' => 'tipo.id','select_text' => 'tipo.nome'])
    @include('admin.include.forms.input-text',['input_size'=>3,'input'=>'"data_ordenacao"','input_label'=>'Data de Ordenação:','type' => 'date', 'place_holder'=>'01/01/1990','vue_var'=>'registro.data_ordenacao'])
    {{-- @include('admin.include.forms.input-datetime',['input_size' => 3, 'input' => '"data_ordenacao"','input_label' => 'Data de Ordenação::','place_holder' => 'Data de Ordenação:', 'vue_var' => 'registro.data_ordenacao']) --}}
</div>
<div v-if="registro.tipo == 1">
    <div class="row">
        @include('admin.include.input-checkbox',['input_size' => 3,'input' => 'thumb','vue_var' => 'registro.thumb','input_label' => 'Thumb Listagem?'])
    </div>
</div>
<div class="row" v-if="registro.tipo != 2">
    @include('admin.include.forms.input-text',['input_size'=>12,'input'=>'"legenda"','input_label'=>'Legenda:', 'place_holder'=>'Legenda','vue_var'=>'registro.legenda'])
</div>
@include('admin.assets.shared.form-media')