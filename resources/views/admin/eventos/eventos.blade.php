@extends('layouts.admin')

@section('article_class', 'items-list-page')

@section('content')
    <div class="title-block">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Eventos</li>
            </ol>
        </nav>
    </div>
    @include('admin.include.search', ['route' => 'admin.eventos.adicionar'])
    @include('admin.include.forms.messages')
    <div class="card items">
        <div class="row" v-if="carregando">
            @include('admin.include.loader')
        </div>
        <ul class="item-list striped">
            <li class="item item-list-header">
                <div class="item-row">
                    <div class="item-col item-col-header">
		                <div><span>Nome</span></div>
	                </div>
                    <div class="item-col item-col-header">
                        <div><span>Título</span></div>
                    </div>
                    <div class="item-col item-col-header">
                        <div><span>Autor</span></div>
                    </div>
                    <div class="item-col item-col-header">
		                <div><span>Categoria</span></div>
	                </div>
	                <div class="item-col item-col-header">
		                <div class="no-overflow"><span>Ativo</span></div>
	                </div>
                    <div class="item-col item-col-header fixed"><div><span>Ações</span></div></div>
                </div>
            </li>
            <li class="item" v-for="(registro, index ) in registros.data">
                <div class="item-row">
                    <div class="item-col">
		                <div class="item-heading">Nome</div>
		                <div>
			                <a v-bind:href="registro.link" v-bind:title="'Editar ' + registro.nome">
				                <h4 class="item-title"> @{{registro.nome}} </h4>
			                </a>
		                </div>
	                </div>
	                <div class="item-col">
		                <div class="item-heading">Título</div>
		                <div>
			                <a v-bind:href="registro.link" v-bind:title="'Editar ' + registro.titulo">
				                <h4 class="item-title"> @{{registro.titulo}} </h4>
			                </a>
		                </div>
	                </div>
	                <div class="item-col">
		                <div class="item-heading">Autor</div>
		                <div>@{{registro.autor}}</div>
	                </div>
                    <div class="item-col">
                        <div class="item-heading">Categoria</div>
                        <div>@{{registro.categoria != null ? registro.categoria.nome : 'Nenhum categoria selecionada'}}</div>
                    </div>
	                <div class="item-col pull-center">
		                <label class="toggle-on-of">
			                <input type="checkbox" @click="clickAtivo(registro.id)" v-model="registro.ativo">
			                <span class="toggle-on-of-slider"></span>
		                </label>
	                </div>
                    <div class="item-col fixed item-col-actions-normal">
                        <ul class="item-actions-list">
                            <li>
                                <a class="edit" v-bind:href="registro.link" v-bind:title="'Editar ' + registro.nome"><i class="fa fa-pencil"></i></a>
                            </li>
                            <li>
                                <a class="remove" @click="clickDelete(registro.id, index)" v-bind:title="'Deletar ' + registro.nome"><i class="fa fa-trash-o "></i></a>
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
    <script src="{{ url('js/admin/mixins/evento.js') }}"></script>
    <script src="{{ url('js/admin/mixins/listing.js') }}"></script>
    <script src="{{ url('js/admin/mixins/delete.js') }}"></script>
    <script src="{{ url('js/admin/eventos.js') }}"></script>
@endsection

