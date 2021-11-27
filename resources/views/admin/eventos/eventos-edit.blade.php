@extends('layouts.admin')

@section('article_class', 'item-editor-page')

@section('content')
<div class="title-block">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.eventos')}}">Eventos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>
    <span class="sparkline bar" data-type="bar"></span>
</div>
@include('admin.include.forms.messages')
<form id="form-evento" name="form-evento" v-on:submit.prevent enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="card card-block">
        @include('admin.eventos.shared.form')
        @include('admin.include.forms.edit',['save_method' => 'editEvento()', 'back_method' => 'backMethod'])
    </div>
</form>
@endsection

@section('script')
    <script> var id = '{{$id}}'; </script>
    <script src="{{ url('js/admin/mixins/errors.js') }}"></script>
    <script src="{{ url('js/admin/mixins/edit.js') }}"></script>
    <script src="{{ url('js/admin/mixins/evento.js') }}"></script>
    <script src="{{ url('js/admin/mixins/eventoLista.js') }}"></script>
    <script src="{{ url('js/admin/mixins/messages.js') }}"></script>
    <script src="{{ url('js/admin/mixins/media.js') }}"></script>
    <script src="{{ url('js/admin/eventos-edit.js') }}"></script>
@endsection
