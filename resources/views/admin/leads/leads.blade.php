@extends('layouts.admin')

@section('article_class','items-list-page')

@section('content')
<div class="title-block">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Leads</li>
		</ol>
	</nav>
</div>
<div class="title-search-block">
	{{-- <div class="title-block">
		<div class="row">
            <div class="col-md-6">
                <div class="col-md-2" v-cloak v-if="!show_filter && !show_filter_origem">
                    <h3 class="title">
                        <button class="btn btn-primary btn-sm rounded-s" v-on:click="donwloadLeads"> Download Leads</button>
                    </h3>
                </div>
                <div class="col-md-4" v-cloak v-if="!show_filter && !show_filter_origem">
                    <h3 class="title">
                        <button class="btn btn-secondary btn-sm btn-block rounded-s" style="text-align:left" v-on:click="showFilterOrigem">Filtrar Leads por Origem</button>
                    </h3>
                </div>
            </div>
        </div>
		<div class="row">
            <div class="col-md-6" v-cloak v-if="show_filter_origem">
                <div class="form-group col-sm-6">
                    <label class="form-control-label text-xs-right" for="filtro">Filtro por Origem: </label>
                    <select id="filtro" name="filtro" class="c-select form-control boxed" v-model="filtro">
                        <option disabled selected value="0">Escolha um filtro</option>
                        <option value="site_institucional">Site institucional</option>
                        <option value="contato_site">Contato site</option>
                    </select>
                </div>
                <div class="col-md-6" v-cloak v-if="show_filter_origem">
                    <h3 class="title">
                        <button class="btn btn-primary btn-sm rounded-s" v-on:click="donwloadLeadsFiltro"> Download Leads com Filtro</button>
                        <button class="btn btn-danger btn-sm rounded-s" v-on:click="donwloadLeadsCancelar"> Cancelar</button>
                    </h3>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-md-6">
				<h3 class="title">
					<button class="btn btn-primary btn-sm rounded-s" v-on:click="donwloadLeads"> Download Leads</button>
				</h3>
			</div>
		</div>
	</div> --}}
	{{-- <div class="card card-default" v-cloak v-if="show_result">
		<div class="row">
			<div class="col-md-10">
				<div class="card-header">
					<div class="header-block">
						<p class="title"> Resultado Busca</p>
					</div>
				</div>
				<div class="card-block">
					<p>@{{ result_message }}</p>
				</div>
			</div>
			<div class="col-md-2">
				<button type="button" class="btn btn-oval btn-danger close-result-lead" @click="closeResults">X</button>
			</div>
		</div>
	</div> --}}
	<div class="dashboard-page">
		<div class="card stats" data-exclude="xs">
			<div class="card-block">
				<div class="row">
					<div class="col-md-12">
						<div class="row row-sm stats-container">
							<div class="col-12 col-sm-12 stat-col">
								<div class="stat-icon">
									<i class="fa fa-envelope"></i>
								</div>
								<div class="stat">
									<div class="name"> Total de Contatos </div>
									<div class="value"> @{{ totalLeads }} </div>
								</div>
								<div class="progress stat-progress">
									<div class="progress-bar" :style="'width: '+porcentagemLeads + '%;'"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="items-search">
		<form class="form-inline" v-on:submit="search($event)">
			<div class="input-group">
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
					<div><span>Email</span></div>
				</div>
				<div class="item-col item-col-header">
					<div class="no-overflow"><span>Documento</span></div>
				</div>
				<div class="item-col item-col-header">
					<div class="no-overflow"><span>Mensagem</span></div>
				</div>
				<div class="item-col item-col-header">
					<div class="no-overflow"><span>Data</span></div>
				</div>
				<div class="item-col item-col-header fixed"><div><span>Ações</span></div></div>
			</div>
		</li>
		<li class="item" v-for="(registro, index ) in registros.data">
			<div class="item-row">
				<div class="item-col pull-left ">
					<div class="item-heading">Email</div>
					<div>
						<h4 class="item-title">@{{ registro.email }}</h4>
					</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Documento</div>
					<div>@{{ registro.documento }}</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Mensagem</div>
					<div :title="registro.mensagem">@{{registro.mensagem ? registro.mensagem.length <= 50 ? registro.mensagem : registro.mensagem.substr(0,50)+"..." : ''}}</div>
				</div>
				<div class="item-col">
					<div class="item-heading">Data Criação</div>
					<div class="no-overflow"> @{{formatDate(registro.created_at)}} </div>
				</div>
				<div class="item-col fixed item-col-actions-normal">
					<ul class="item-actions-list">
						<li>
							<a class="remove" @click="clickDelete(registro.id, index)" v-bind:title="registro.email"><i class="fa fa-trash-o "></i></a>
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
<script src="{{ url('js/admin/lead.js') }}"></script>
@endsection
