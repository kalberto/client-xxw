<div class="row">
    @include('admin.include.forms.input-text',['input_size' => 6, 'input' => '"email"','input_label' => 'Email:','place_holder'=>'Email','vue_var'=>'registro.email','disable'=>true])
    @include('admin.include.forms.input-text',['input_size' => 6, 'input' => '"password"','input_label' => 'Senha usuário logado:','place_holder' => 'Senha usuário logado', 'vue_var' => 'registro.password','type'=>'password'])
</div>
<div class="row">
    @include('admin.include.forms.input-text',['input_size' => 6, 'input' => '"new_password"','input_label' => 'Nova senha:','place_holder' => 'Nova senha', 'vue_var' => 'registro.new_password','type'=>'password'])
    @include('admin.include.forms.input-text',['input_size' => 6, 'input' => '"re_password_confirmation"','input_label' => 'Confirmação da nova senha:','place_holder' => 'Confirme a nova senha', 'vue_var' => 'registro.re_password_confirmation','type'=>'password'])
</div>