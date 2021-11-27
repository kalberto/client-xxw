@extends('layouts.admin')

@section('article_class', 'items-list-page')

@section('content')
<div class="title-block">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Administradores</li>
        </ol>
    </nav>
</div>
@include('admin.include.search', ['route' => 'admin.administradores.adicionar'])
@include('admin.include.forms.messages')
<div class="card itens">
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
                    <div class="no-overflow"><span>E-mail</span></div>
                </div>
                <div class="item-col item-col-header">
                    <div class="no-overflow"><span>Perfil</span></div>
                </div>
                <div class="item-col item-col-header">
                    <div class="no-overflow"><span>Status</span></div>
                </div>
                <div class="item-col item-col-header fixed"><div><span>Ações</span></div></div>
            </div>
        </li>
        <li class="item" v-for="(administrador, index ) in registros.data">
            <div class="item-row">
                <div class="item-col pull-left ">
                    <div class="item-heading">Nome</div>
                    <div>
                        <a v-bind:href="administrador.link" v-bind:title="administrador.nome">
                            <h4 class="item-title">@{{administrador.nome}}</h4>
                        </a>
                    </div>
                </div>
                <div class="item-col">
                    <div class="item-heading">E-mail</div>
                    <div>@{{administrador.email}}</div>
                </div>
                <div class="item-col">
                    <div class="item-heading">Perfil</div>
                    <div>@{{administrador.perfil.nome}}</div>
                </div>
                <div class="item-col">
                    <div class="item-heading">Ativo</div>
                    <div>@{{(administrador.ativo ? 'Sim' : 'Não')}}</div>
                </div>
                <div class="item-col fixed item-col-actions-normal">
                    <ul class="item-actions-list">
                        <li>
                            <a class="remove" @click="clickDelete(administrador.id, index)" v-bind:title="'Deletar: ' + administrador.nome"><i class="fa fa-trash-o "></i></a>
                        </li>
                        <li>
                            <a class="edit" v-bind:href="administrador.link" v-bind:title="'Editar: ' + administrador.nome"><i class="fa fa-pencil"></i></a>
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
    <script src="{{ url('js/admin/administradores.js') }}"></script>
@endsection

