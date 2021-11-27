@extends('layouts.admin')

@section('article_class','items-list-page')

@section('content')
<div class="title-block">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Usuarios</li>
		</ol>
	</nav>
</div>
<div class="title-search-block">
	<div class="dashboard-page">
		<div class="card stats" data-exclude="xs">
			<div class="card-block">
				<div style="display: flex; flex-wrap: wrap; width: 40%">
					<div style="margin-right: 5px">
						<h3 class="title">
							<a href="{{route('admin.usuarios.adicionar')}}" class="btn btn-primary btn-sm rounded-s"> Adicionar Novo </a>
						</h3>
					</div>
					<div style="margin-right: 5px">
						<a href="{{url('admin/relatorios/usuarios')}}" target="_blank" class="btn btn-primary btn-sm rounded-s">Download relatório</a>
					</div>
					<div style="margin-right: 5px">
						<a href="#" class="btn btn-primary btn-sm rounded-s" v-on:click="openModalUploadMeta()">Upload planilha meta</a>
					</div>
					<div style="margin-right: 5px">
						<a href="#" class="btn btn-primary btn-sm rounded-s" v-on:click="openModalUploadClassificacao()">Upload Classificação</a>
					</div>
					<div style="margin-right: 5px">
						<a href="#" class="btn btn-primary btn-sm rounded-s" v-on:click="openModalUploadSaldoVpc()">Upload Saldo VPC</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="row row-sm stats-container">
							<div class="col-12 col-sm-12 stat-col">
								<div class="stat">
									<div class="name"> Total de Usuários </div>
									<div class="value"> @{{totalUsuarios}} </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="items-search" style="margin-top: 15px;margin-right: 15px; display: flex; flex-wrap: wrap; width: 45%;">
		@if($super_adm)<a href="#" class="btn btn-primary btn-sm rounded-s" v-on:click="openModalUpload()">Upload planilha super</a>@endif
		<div style="margin-right: 10px; display:flex; align-items: center">
			<label class="control-label" for="select-status" style="padding-right: 10px">Status:</label>
			<select id="select-status" class="form-control" v-model="pagination.state" @change="load">
				<option value="">Todos</option>
				<option v-for="item in status" v-bind:value="item.status">@{{ item.texto_status }}</option>
			</select>
		</div>
		<div style="margin-right: 10px; display:flex; align-items: center">
			<label class="control-label" for="select-status" style="padding-right: 10px">Conta:</label>
			<select id="select-status" class="form-control" v-model="pagination.conta" @change="load">
				<option value="">Todos</option>
				<option v-for="item in contas" v-bind:value="item.valor">@{{ item.texto }}</option>
			</select>
		</div>

		<form class="form-inline" v-on:submit="search($event)">
			<div class="input-group" style="margin: 0">
				<input type="text" id="search_input" v-model="pagination.q" class="form-control boxed rounded-s" placeholder="Busque por">
				<span class="input-group-btn">
					<button class="btn btn-secondary rounded-s botao-busca" type="submit">
						<i class="fa fa-search"></i>
					</button>
				</span>
			</div>
		</form>
	</div>
</div>
@include('admin.include.forms.messages')
<div class="card itens">
	<ul class="item-list striped">
		<li class="item item-list-header">
			<div class="item-row">
				<div class="item-col item-col-header">
					<div><span>Nome</span></div>
				</div>
				<div class="item-col item-col-header">
					<div><span>Nome Fantasia</span></div>
				</div>
				<div class="item-col item-col-header">
					<div class="no-overflow"><span>Razão Social</span></div>
				</div>
				<div class="item-col item-col-header">
					<div class="no-overflow"><span>Documento</span></div>
				</div>
				<div class="item-col item-col-header">
					<div class="no-overflow"><span>Cidade-UF</span></div>
				</div>
				<div class="item-col item-col-header">
					<div class="no-overflow"><span>Nível</span></div>
				</div>
				<div class="item-col item-col-header text-center">
					<div class="no-overflow"><span>Ativo</span></div>
				</div>
				<div class="item-col item-col-header text-center">
					<div class="no-overflow"><span>Conta</span></div>
				</div>
				<div class="item-col item-col-header fixed"><div><span>Ações</span></div></div>
			</div>
		</li>
		<li class="item" v-for="(registro, index ) in registros.data">
			<div class="item-row">
				<div class="item-col pull-left ">
					<div class="item-heading">Nome</div>
					<div>
						<a v-bind:href="registro.link" v-bind:title="'Editar ' + registro.nome">
							<h4 class="item-title">@{{(registro.nome ? registro.nome.length <= 15 ? registro.nome : registro.nome.substr(0,15) + "..." : "")}}</h4>
						</a>
					</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Nome Fantasia</div>
					<div>
						<a v-bind:href="registro.link" v-bind:title="'Editar ' + registro.nome_fantasia">
							<h4 class="item-title"> @{{(registro.nome_fantasia ? registro.nome_fantasia.length <= 20 ? registro.nome_fantasia : registro.nome_fantasia.substr(0,20) + "..." : "")}} </h4>
						</a>
					</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Razão Social</div>
					<div>
						<a v-bind:href="registro.link" v-bind:title="'Editar ' + registro.razao_social">
							<h4 class="item-title"> @{{registro.razao_social}} </h4>
						</a>
					</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Documento</div>
					<div>
						<a v-bind:href="registro.link" v-bind:title="'Editar ' + getDocumento(registro.documento)">
							<h4 class="item-title"> @{{getDocumento(registro.documento)}} </h4>
						</a>
					</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Cidade-Uf</div>
					<div>@{{registro.cidade}}</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Nível</div>
					<div>@{{registro.nivel}}</div>
				</div>
				<div class="item-col" v-bind:class="getAtivoCor(registro.ativo)" style="margin-right: 1px;">
					<div class="item-heading">Ativo</div>
					<div>@{{registro.ativo ? 'Ativo' : 'Desativado' }}</div>
				</div>
				<div class="item-col" v-bind:class="getAtivoCor(registro.conta_atualizada)" style="">
					<div class="item-heading">conta_atualizada</div>
					<div>@{{registro.conta_atualizada ? 'Atualizada' : 'Pendente' }}</div>
				</div>
				<div class="item-col fixed item-col-actions-normal">
					<ul class="item-actions-list">
						<li>
							<a class="edit" v-bind:href="registro.link" v-bind:title="'Editar ' + registro.nome"><i class="fa fa-pencil"></i></a>
						</li>
						<li>
							<a class="edit" v-bind:href="registro.acesso_link" v-bind:title="'Acessar ' + registro.nome" target="_blank"><i class="fa fa-user"></i></a>
						</li>
					</ul>
				</div>
			</div>
		</li>
	</ul>
	@include('admin.include.modal-delete')
</div>
@include('admin.usuarios.include.modal-upload')
@include('admin.usuarios.include.modal-upload-meta')
@include('admin.usuarios.include.modal-upload-classificacao')
@include('admin.usuarios.include.modal-upload-saldo-vpc')
@include('admin.include.pagination', ['paginador' => 'registros'])
@endsection
@section('script')
<script src="{{ url('js/admin/mixins/main.js') }}"></script>
<script src="{{ url('js/admin/mixins/messages.js') }}"></script>
<script src="{{ url('js/admin/mixins/listing.js') }}"></script>
<script src="{{ url('js/admin/mixins/delete.js') }}"></script>
<script src="{{ url('js/admin/usuarios.js') }}"></script>
@endsection
