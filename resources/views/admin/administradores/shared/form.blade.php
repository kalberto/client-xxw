<div class="row">
    @include('admin.include.forms.input-checkbox',['input_size'=>6,'input' => '"ativo"', 'vue_var' => 'registro.ativo','input_label'=>'Ativo'])
    @include('admin.include.forms.input-text',['input_size' => 12, 'input' => '"nome"','input_label' => 'Nome:','place_holder' => 'Nome', 'vue_var' => 'registro.nome'])
    @include('admin.include.forms.input-text',['input_size' => 12, 'input' => '"sobrenome"','input_label' => 'Sobrenome:','place_holder' => 'Sobrenome', 'vue_var' => 'registro.sobrenome'])
</div>
<div class="row">
    @include('admin.include.forms.input-text',['input_size' => 3, 'input' => '"telefone"','input_label' => 'Telefone:','place_holder' => 'Telefone', 'vue_var' => 'registro.telefone','mask'=>"['(##) ####-####', '(##) #####-####']"])
    @include('admin.include.forms.input-text',['input_size' => 3, 'input' => '"celular"','input_label' => 'Celular:','place_holder' => 'Celular', 'vue_var' => 'registro.celular','mask'=>"['(##) ####-####', '(##) #####-####']"])
    @include('admin.include.forms.input-select',['input_size' => 6,'input' => '"perfil_id"','input_label' => 'Perfil','vue_var' => 'registro.perfil_id','v_for' => 'perfil in perfis','select_value' => 'perfil.id','select_text' => 'perfil.nome'])
</div>
<div class="row">
</div>
