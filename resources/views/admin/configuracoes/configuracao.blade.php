@extends('layouts.admin')

@section('article_class', 'item-editor-page')

@section('content')
<div class="title-block">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Configuracoes</li>
        </ol>
    </nav>
    <span class="sparkline bar" data-type="bar"></span>
</div>
@include('admin.include.forms.messages')
<form id="form-configuracao" name="form-configuracao" v-on:submit.prevent enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="card card-default">
        <div class="card-header">
            <div class="header-block">
                <p class="title">Seo</p>
            </div>
        </div>
        {{-- <div class="card-block">
            @include('admin.configuracoes.shared.form-seo')
        </div> --}}
    </div>
    <div class="card card-block">
        @include('admin.configuracoes.shared.form')
	</div>
	<div class="card card-block">
        @include('admin.include.forms.edit',['save_method' => 'editConfiguracao()', 'route_back' => 'admin.configuracoes'])
    </div>
    {{-- <div class="card card-block">
        <div class="form-group row">
            <div class="col-sm-1">
                <div v-if="carregando_edit">@{{sending_message}}</div>
                <button type="submit" class="btn btn-primary" v-else> Atualizar </button>
            </div>
            <div class="col-sm-1">
                <button type="button" class="btn btn-secondary-outline"><a href="{{route('admin')}}" v-if="!carregando_edit"> Voltar </a></button>
            </div>
        </div>
    </div> --}}
</form>
@include('admin.configuracoes.shared.modal-media-configuracao')
@endsection

@section('script')
    <script src="{{ url('js/admin/mixins/errors.js') }}"></script>
    <script src="{{ url('js/admin/mixins/edit.js') }}"></script>
    <script src="{{ url('js/admin/mixins/messages.js') }}"></script>
    <script src="{{ url('js/admin/mixins/media.js') }}"></script>
    <script src="{{ url('js/admin/configuracoes.js') }}"></script>
@endsection
