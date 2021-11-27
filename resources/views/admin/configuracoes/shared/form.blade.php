
<div class="row">
	@include('admin.include.forms.input-text',['input_size' => 12, 'max' => 45, 'input' => '"nome_app"','input_label' => 'Nome do aplicativo:','place_holder' => 'Nome do aplicativo', 'vue_var' => 'registro.nome_app', 'vue_text' => 'registro.nome_app'])
</div>
<div class="row">
	@include('admin.include.forms.input-text',['input_size' => 6, 'max' => 145, 'input' => '"email_destinatario"','input_label' => 'E-mail destinatário::','place_holder' => 'E-mail destinatário:', 'vue_var' => 'registro.email_destinatario'])
	@include('admin.include.forms.input-text',['input_size' => 6, 'max' => 145, 'input' => '"email_remetente"','input_label' => 'E-mail remetente:','place_holder' => 'E-mail remetente:', 'vue_var' => 'registro.email_remetente'])
</div>
