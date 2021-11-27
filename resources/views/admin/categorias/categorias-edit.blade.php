@extends('layouts.admin')

@section('article_class', 'item-editor-page')

@section('content')
<div class="title-block">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.categorias')}}">Categorias</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>
    <span class="sparkline bar" data-type="bar"></span>
</div>
@include('admin.include.forms.messages')
<form id="form-categoria" name="form-categoria" v-on:submit.prevent enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="card card-block">
        @include('admin.categorias.shared.form')
        @include('admin.include.forms.edit',['save_method' => 'editCategoria()', 'route_back' => 'admin.categorias'])
    </div>
</form>
@endsection

@section('script')
<script> var id = '{{$id}}'; </script>
<script src="{{ url('js/admin/mixins/errors.js') }}"></script>
<script src="{{ url('js/admin/mixins/edit.js') }}"></script>
<script src="{{ url('js/admin/mixins/categoria.js') }}"></script>
<script src="{{ url('js/admin/mixins/messages.js') }}"></script>
<script src="{{ url('js/admin/mixins/media.js') }}"></script>
<script src="{{ url('js/admin/categorias-edit.js') }}"></script>
@endsection
