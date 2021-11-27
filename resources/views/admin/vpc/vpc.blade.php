@extends('layouts.admin')

@section('article_class','items-list-page')

@section('content')
<div class="title-block">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Solicitações de VPC</li>
		</ol>
	</nav>
</div>
<div class="title-search-block">
	<div class="items-search" style="margin-top: 15px;margin-right: 15px; display: flex;">
		<!--<div style="margin-right: 10px; display:flex; align-items: center">
			<label class="control-label" for="select-status" style="padding-right: 10px">Status:</label>
			<select id="select-status" class="form-control" v-model="pagination.state" @change="load">
				<option value="">Todos</option>
				<option v-for="item in status" v-bind:value="item.status">@{{ item.status }}</option>
			</select>
		</div>-->
	</div>
</div>
@include('admin.include.forms.messages')
<div class="card itens">
	<ul class="item-list striped">
		<li class="item item-list-header">
			<div class="item-row">
				<div class="item-col item-col-header">
					<div><span>Ação</span></div>
				</div>
				<div class="item-col item-col-header">
					<div><span>Documento</span></div>
				</div>
				<div class="item-col item-col-header">
					<div><span>Razão Social</span></div>
				</div>
				<div class="item-col item-col-header">
					<div><span>Custo</span></div>
				</div>
				<div class="item-col item-col-header">
					<div><span>Solicitado</span></div>
				</div>
				<div class="item-col item-col-header">
					<div><span>Atualizado</span></div>
				</div>
				<div class="item-col item-col-header text-center">
					<div class="no-overflow"><span>Status</span></div>
				</div>
				<div class="item-col item-col-header fixed"><div><span>Ações</span></div></div>
			</div>
		</li>
		<li class="item" v-for="(registro, index ) in registros.data">
			<div class="item-row">
				<div class="item-col pull-left ">
					<div class="item-heading">Ação</div>
					<div>
						<a v-bind:href="registro.link" v-bind:title="'Editar ' + registro.acao">
							<h4 class="item-title">@{{registro.acao}}</h4>
						</a>
					</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Documento</div>
					<div>@{{getDocumento(registro.documento)}}</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Razão Social</div>
					<div>@{{registro.razao_social}}</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Custo</div>
					<div>@{{registro.dados.custo}}</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Solicitado</div>
					<div>@{{registro.created_at}}</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Atualizado</div>
					<div>@{{registro.created_at}}</div>
				</div>
				<div class="item-col" v-bind:class="registro.status.status" style="margin-right: 1px;">
					<div class="item-heading">Status</div>
					<div>@{{registro.status.nome }}</div>
				</div>
				<div class="item-col fixed item-col-actions-normal">
					<ul class="item-actions-list">
						<li>
							<a class="edit" v-bind:href="registro.link" v-bind:title="'Editar ' + registro.nome"><i class="fa fa-pencil"></i></a>
						</li>
					</ul>
				</div>
			</div>
		</li>
	</ul>
</div>
@include('admin.include.pagination', ['paginador' => 'registros'])
@endsection
@section('script')
<script src="{{ url('js/admin/mixins/main.js') }}"></script>
<script src="{{ url('js/admin/mixins/messages.js') }}"></script>
<script src="{{ url('js/admin/mixins/listing.js') }}"></script>
<script src="{{ url('js/admin/vpcs.js') }}"></script>
@endsection
