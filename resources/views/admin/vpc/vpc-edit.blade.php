@extends('layouts.admin')

@section('article_class', 'item-editor-page')

@section('content')
<div class="title-block">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.vpc')}}">Solicitações de VPC</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>
    <span class="sparkline bar" data-type="bar"></span>
</div>
@include('admin.include.forms.messages')
<div class="card card-block">
	<div class="card card-default">
		<div class="card-header">
			<div class="header-block">
				<p class="title">
					Ação - @{{vpc.acao}}
				</p>
			</div>
		</div>
		<div class="card-block">
			<div class="card-header">
				<div class="header-block">
					<p class="title">
						Status
					</p>
				</div>
			</div>
			<div style="display: flex;">
				<div class="status-vpc" v-for="(item, index ) in vpc.all_status" v-bind:class="item.status">
					<p>Status: @{{item.nome}}</p>
					<p>Data: @{{item.criado}}</p>
				</div>
			</div>
		</div>
		<div class="subtitle-block"></div>
		<div class="card-block" v-if="vpc.usuario">
			<div class="card-header">
				<div class="header-block">
					<p class="title">
						Cliente/Usuário
					</p>
				</div>
			</div>
			<div class="container-campos">
				<div class="campo _40">
					<p class="container-line"><strong>Razão Social:</strong></p>
					<p class="container-line">@{{vpc.usuario.razao_social}}</p>
				</div>
				<div class="campo _40">
					<p class="container-line"><strong>Documento:</strong></p>
					<p class="container-line">@{{getDocumento(vpc.usuario.documento)}}</p>
				</div>
				<div class="campo _40">
					<p class="container-line"><strong>Grupo:</strong></p>
					<p class="container-line">@{{vpc.usuario.nome}}</p>
				</div>
				<div class="campo _40">
					<p class="container-line"><strong>Email:</strong></p>
					<p class="container-line">@{{vpc.usuario.email}}</p>
				</div>
			</div>
		</div>
		<div class="subtitle-block"></div>
		<div class="card-block">
			<div class="card-header">
				<div class="header-block">
					<p class="title">
						Custos
					</p>
				</div>
			</div>
			<div class="container-campos">
				<div class="campo _40">
					<p class="container-line"><strong>Custo Total:</strong></p>
					<p class="container-line">@{{vpc.dados.custo}}</p>
				</div>
			</div>
			<div class="card-block">
				<p class="container-line"><strong>Detalhamento da utilização dos saldos:</strong></p>
				<ul class="nav nav-tabs nav-tabs-bordered">
					<li class="nav-item" v-for="item in vpc.custos">
						<a :href="'#'+item.mes" class="nav-link" :data-target="'#'+item.mes" data-toggle="tab" :aria-controls="item.mes" role="tab" aria-selected="true">@{{ item.mes +' - '+ item.ano}}</a>
					</li>
				</ul>
				<div class="tab-content tabs-bordered">
					<div v-for="(item,index) in vpc.custos" class="tab-pane fade" :id="item.mes">
						<div class="container-campos">
							<div class="campo _50">
								<p class="container-line"><strong>Validade:</strong></p>
								<p class="container-line">@{{item.validade}}</p>
							</div>
							<div class="campo _50">
								<p class="container-line"><strong>Saldo Total:</strong></p>
								<p class="container-line">@{{item.saldo_total}}</p>
							</div>
						</div>
						<div class="container-campos">
							<div class="campo _50">
								<p class="container-line"><strong>Saldo Provisionado (Total):</strong></p>
								<p class="container-line">@{{item.saldo_provisionado}}</p>
							</div>
							<div class="campo _50">
								<p class="container-line"><strong>Saldo Provisionado (VPC atual):</strong></p>
								<p class="container-line">@{{item.saldo_provisionado_atual}}</p>
							</div>
						</div>
						<div class="container-campos">
							<div class="campo _50">
								<p class="container-line"><strong>Saldo Disponivel:</strong></p>
								<p class="container-line">@{{item.saldo_disponivel}}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="subtitle-block"></div>
		<div class="card-block">
			<div class="card-header">
				<div class="header-block">
					<p class="title">
						Dados da Solicitação
					</p>
				</div>
			</div>
			<div class="container-campos">
				<div class="campo _50" v-for="item in vpc.campos" v-if="item.tipo !== 'files'">
					<p class="container-line"><strong>@{{item.label}}:</strong></p>
					<p class="container-line">@{{vpc.dados[item.campo]}}</p>
				</div>
			</div>
			<div>
				<p class="title">
					Arquivos - Anexos
				</p>
				<table class="table table-bordered">
					<thead>
					<tr>
						<th>#</th>
						<th>Nome</th>
						<th>Link</th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="(item,index) in vpc.anexos">
						<th scope="row">@{{ index }}</th>
						<td>@{{ item.name }}</td>
						<td><a :href="item.file" target="_blank">Download</a></td>
					</tr>
					</tbody>
				</table>
			</div>
			<div>
				<p class="title">
					Comprovantes
				</p>
				<table class="table table-bordered">
					<thead>
					<tr>
						<th>#</th>
						<th>Nome</th>
						<th>Link</th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="(item,index) in vpc.comprovantes">
						<th scope="row">@{{ index }}</th>
						<td>@{{ item.name }}</td>
						<td><a :href="item.file" target="_blank">Download</a></td>
					</tr>
					</tbody>
				</table>
			</div>
			<div>
				<div style="display: flex;justify-content:space-between;">
					<p class="title">
						Anexos Admin
					</p>
					<div style="margin-right: 5px">
						<a href="#" class="btn btn-primary btn-sm rounded-s" v-on:click="openModalAddAnexoAdmin()">Adicionar Arquivo</a>
					</div>
				</div>

				<table class="table table-bordered">
					<thead>
					<tr>
						<th>#</th>
						<th>Nome</th>
						<th>Link</th>
						<th>Deletar</th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="(item,index) in vpc.anexos_admin">
						<th scope="row">@{{ index }}</th>
						<td>@{{ item.name }}</td>
						<td><a :href="item.file" target="_blank">Download</a></td>
						<td>
							<a class="remove" @click="clickDelete(item.id, index)" v-bind:title="'Deletar'"><i class="fa fa-trash-o "></i></a>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<form id="form-vpc" name="form-vpc" v-on:submit.prevent enctype="multipart/form-data" >
    {{csrf_field()}}
	<div class="card card-block" v-if="vpc.status.status === 'pendente'">
		<div class="card-header">
			<div class="header-block">
				<p class="title">
					Descreva o motivo da sua ação e escolha uma
				</p>
			</div>
		</div>
		@include('admin.include.forms.text-area',['input_size'=>12,'input'=>'"comentarios"','input_label'=>'Comentários para o usuário:','place_holder' => '','vue_var'=>'registro.comentarios','rows' => 4])
		<div class="subtitle-block"></div>
		<div class="form-group">
			<div class="row">
				<div class="col-sm-2 col-sm-offset-2">
					<div v-if="sending">@{{sendingMessage}}</div>
					<button v-else type="submit" class="btn btn-primary" @click="aprovarVPC()"> Aprovar </button>
				</div>
				<div class="col-sm-2 col-sm-offset-2">
					<div v-if="sending">@{{sendingMessage}}</div>
					<button v-else type="submit" class="btn btn-danger" @click="reprovarVPC()"> Reprovar </button>
				</div>
				<div class="col-sm-2 col-sm-offset-2">
					<div v-if="sending">@{{sendingMessage}}</div>
					<button v-else type="submit" class="btn btn-warning" @click="revisarVPC()"> Revisão </button>
				</div>
			</div>
		</div>
	</div>
	<div class="card card-block" v-if="vpc.status.status === 'comprovado'">
		<div class="card-header">
			<div class="header-block">
				<p class="title">
					Descreva o motivo da sua ação e escolha uma
				</p>
			</div>
		</div>
		@include('admin.include.forms.text-area',['input_size'=>12,'input'=>'"comentarios"','input_label'=>'Comentários para o usuário:','place_holder' => '','vue_var'=>'registro.comentarios','rows' => 4])
		<div class="subtitle-block"></div>
		<div class="form-group">
			<div class="row">
				<div class="col-sm-2 col-sm-offset-2">
					<div v-if="sending">@{{sendingMessage}}</div>
					<button v-else type="submit" class="btn btn-danger" @click="recusarVPC()"> Recusar </button>
				</div>
				<div class="col-sm-2 col-sm-offset-2">
					<div v-if="sending">@{{sendingMessage}}</div>
					<button v-else type="submit" class="btn btn-success" @click="pagarVPC()"> Pagar </button>
				</div>
			</div>
		</div>
	</div>
</form>
@include('admin.include.modal-delete')
@include('admin.vpc.include.modal-upload')
@endsection

@section('script')
<script> var id = '{{$id}}'; </script>
<script src="{{ url('js/admin/mixins/errors.js') }}"></script>
<script src="{{ url('js/admin/mixins/edit.js') }}"></script>
<script src="{{ url('js/admin/mixins/vpc.js') }}"></script>
<script src="{{ url('js/admin/mixins/messages.js') }}"></script>
<script src="{{ url('js/admin/mixins/media.js') }}"></script>
<script src="{{ url('js/admin/mixins/delete.js') }}"></script>
<script src="{{ url('js/admin/vpc-edit.js') }}"></script>
@endsection
