<div>
	<div>
		<h5>Dados Conta</h5>
	</div>
	<div class="row">
		@include('admin.include.forms.input-checkbox',['input_size' => 2,'input'=>'"ativo"','vue_var'=>'registro.ativo','input_label'=>'Conta ativa'])
		@include('admin.include.forms.input-checkbox',['input_size' => 2,'input'=>'"conta_atualizada"','vue_var'=>'registro.conta_atualizada','input_label'=>'Conta atualizada','disable' => true])
		@include('admin.include.forms.input-checkbox',['input_size' => 2,'input'=>'"teste"','vue_var'=>'registro.teste','input_label'=>'Conta Teste'])
	</div>
	<div class="row">
		@include('admin.include.forms.input-checkbox',['input_size' => 2,'input'=>'"vpc_disponivel"','vue_var'=>'registro.vpc_disponivel','input_label'=>'VPC Disponível'])
		@include('admin.include.forms.input-money',['input_size' => 2,'place_holder' => '0.000,00','input'=>'"saldo_vpc"','vue_var'=>'registro.saldo_vpc','input_label'=>'Saldo VPC'])
	</div>
</div>
<div class="subtitle-block"></div>
<div>
	<h5>Dados Empresa</h5>
</div>
<div class="row">
	@include('admin.include.forms.input-text',['input_size'=>4,'input'=>'"documento"','input_label'=>'Documento:', 'place_holder'=>'00.000.000./0000-00','vue_var'=>'registro.documento','mask' => "['##.###.###/####-##']",'disable' => (isset($edit) && $edit) ? true : false ])
	@include('admin.include.forms.input-text',['input_size'=>4,'input'=>'"razao_social"','input_label'=>'Razão Social:', 'place_holder'=>'Razão Social','vue_var'=>'registro.razao_social'])
	@include('admin.include.forms.input-text',['input_size'=>4,'input'=>'"nome_fantasia"','input_label'=>'Nome Fantasia:', 'place_holder'=>'Nome Fantasia','vue_var'=>'registro.nome_fantasia'])
	@include('admin.include.forms.input-text',['input_size'=>12,'input'=>'"nome"','input_label'=>'Nome Grupo:', 'place_holder'=>'Nome','vue_var'=>'registro.nome'])
</div>
<div class="subtitle-block"></div>
<div>
	<h5>Dados Usuário</h5>
</div>
<div class="row">
	@include('admin.include.forms.input-text',['input_size'=>3,'input'=>'"contato_responsavel"','input_label'=>'Contato responsável:', 'place_holder'=>'Contato responsável','vue_var'=>'registro.contato_responsavel'])
	@include('admin.include.forms.input-text',['input_size'=> 3,'input'=>'"data_nascimento"','input_label'=>'Data de Nascimento:','type' => 'date', 'place_holder'=>'01/01/1990','vue_var'=>'registro.data_nascimento'])
	@include('admin.include.forms.input-text',['input_size'=>3,'input'=>'"email"','input_label'=>'E-mail:', 'place_holder'=>'E-mail','vue_var'=>'registro.email'])
	@include('admin.include.forms.input-text',['input_size'=>3,'input'=>'"telefone"','input_label'=>'Telefone:', 'place_holder'=>'41 99999-9999','vue_var'=>'registro.telefone','mask' => "['## #####-####']"])
</div>
<div class="row">
	@include('admin.include.forms.input-select',['input_size'=>3, 'input' => '"perfil_id"','input_label' => 'Perfil:', 'vue_var' => 'registro.perfil_id','v_for' => 'perfil in perfis','select_value' => 'perfil.id','select_text' => 'perfil.nome'])
	@include('admin.include.forms.input-select',['input_size'=>3, 'input' => '"nivel_id"','input_label' => 'Nível:', 'vue_var' => 'registro.nivel_id','v_for' => 'nivel in niveis','select_value' => 'nivel.id','select_text' => 'nivel.nome'])
	@include('admin.include.forms.input-select',['input_size'=>3, 'input' => '"estado_id"','input_label' => 'Estado:', 'vue_var' => 'registro.estado_id','v_for' => 'estado in estados','select_value' => 'estado.id','select_text' => 'estado.nome'])
	@include('admin.include.forms.input-select',['input_size'=>3, 'input' => '"cidade_id"','input_label' => 'Cidade:', 'vue_var' => 'registro.cidade_id','v_for' => 'cidade in cidades','select_value' => 'cidade.id','select_text' => 'cidade.nome'])
</div>
<div class="subtitle-block"></div>
<div>
	<div>
		<h5>Permissões da conta</h5>
	</div>
	<div class="row">
		@include('admin.include.forms.input-checkbox',['input_size' => 12,'input'=>'"consentimento_lgpd"','vue_var'=>'registro.consentimento_lgpd','input_label'=>'Consentimento LGPD','disable' => true])
	</div>
</div>
