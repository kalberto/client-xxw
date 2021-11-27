@extends('layouts.admin')
@section('head')

@endsection

@section('header')
@endsection

@section('content')
<section id="content">
    <div class="title-block">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.administradores')}}">Administradores</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>
        <span class="sparkline bar" data-type="bar"></span>
    </div>
    @include('admin.include.forms.messages')
    <form name="form-administrador" v-on:submit.prevent enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="card card-block">
            @include('admin.administradores.shared.form')
            @include('admin.include.forms.save',['save_method' => 'editAdministrador()','route_back' => 'admin.administradores'])
        </div>
    </form>
    @include('admin.include.modal-media')
    <form name="form-administrador-dados" v-on:submit.prevent enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="hidden" name="_method" value="put">
        <div class="divider divider-space"></div>
        {{--  start card-atenção  --}}        
        <div class="card card-warning">
            <div class="card-header">
                <div class="header-block">
                    <p class="title text-white">ATENÇÃO</p>
                </div>
            </div>            
            <div class="card-footer">Cuidado ao editar informações de Dados de acesso</div>
        </div>
        <div class="card card-block">
            @include('admin.administradores.shared.form-dados')
            <div class="form-group row">
                <div class="col-sm-10 col-sm-offset-2">
                    <div v-if="carregando_edit_dados">@{{sending_message_dados}}</div>
                    <button v-else class="btn btn-primary" v-on:click="atualizarAdministradorDados({{$id}})">Atualizar dados de acesso</button>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

@section('script')
    <script> var id = '{{$id}}';</script>
    <script src="{{ url('js/admin/mixins/errors.js') }}"></script>
    <script src="{{ url('js/admin/mixins/edit.js') }}"></script>
    <script src="{{ url('js/admin/mixins/administrador.js') }}"></script>
    <script src="{{ url('js/admin/mixins/messages.js') }}"></script>
    <script src="{{ url('js/admin/mixins/media.js') }}"></script>
    <script src="{{ url('js/admin/administradores-edit.js') }}"></script>
@endsection