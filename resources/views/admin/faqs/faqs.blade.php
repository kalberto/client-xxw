@extends('layouts.admin')

@section('article_class','items-list-page')

@section('content')
<div class="title-block">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Faqs</li>
		</ol>
	</nav>
</div>
<div class="title-search-block">
	<div class="dashboard-page">
		<div class="card stats" data-exclude="xs">
			<div class="card-block">
				<div class="row">
					<div class="col-md-2">
						<h3 class="title">
							<a href="{{route('admin.faqs.adicionar')}}" class="btn btn-primary btn-sm rounded-s"> Adicionar Nova </a>
						</h3>
					</div>
					<div class="col-md-10">
						<div class="row row-sm stats-container">
							<div class="col-12 col-sm-12 stat-col">
								<div class="stat">
									<div class="name"> Total de faqs </div>
									<div class="value"> @{{totalRegistros}} </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="items-search" style="margin-top: 15px;margin-right: 15px; display: flex;">
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
					<div><span>Pergunta</span></div>
				</div>
				<div class="item-col item-col-header">
					<div><span>Resposta</span></div>
				</div>
				<div class="item-col item-col-header fixed"><div><span>Ações</span></div></div>
			</div>
		</li>
		<li class="item" v-for="(registro, index ) in registros.data">
			<div class="item-row">
				<div class="item-col pull-left ">
					<div class="item-heading">Pergunta</div>
					<div>
						<a v-bind:href="registro.link" v-bind:title="'Editar '">
							<h4 class="item-title">@{{(registro.pergunta ? registro.pergunta.length <= 15 ? registro.pergunta : registro.pergunta.substr(0,15) + "..." : "")}}</h4>
						</a>
					</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Resposta</div>
					<div>
						<a v-bind:href="registro.link" v-bind:title="'Editar'">
							<h4 class="item-title"> @{{(registro.resposta ? registro.resposta.length <= 20 ? registro.resposta : registro.resposta.substr(0,20) + "..." : "")}} </h4>
						</a>
					</div>
				</div>
				<div class="item-col fixed item-col-actions-normal">
					<ul class="item-actions-list">
						<li>
							<a class="edit" v-bind:href="registro.link" v-bind:title="'Editar'"><i class="fa fa-pencil"></i></a>
						</li>
						<li>
							<a class="remove" @click="clickDelete(registro.id, index)" v-bind:title="'Deletar'"><i class="fa fa-trash-o "></i></a>
						</li>
					</ul>
				</div>
			</div>
		</li>
	</ul>
	@include('admin.include.modal-delete')
</div>
@include('admin.include.pagination', ['paginador' => 'registros'])
@endsection
@section('script')
<script src="{{ url('js/admin/mixins/main.js') }}"></script>
<script src="{{ url('js/admin/mixins/messages.js') }}"></script>
<script src="{{ url('js/admin/mixins/listing.js') }}"></script>
<script src="{{ url('js/admin/mixins/delete.js') }}"></script>
<script src="{{ url('js/admin/faqs.js') }}"></script>
@endsection
