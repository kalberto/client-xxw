@extends('layouts.admin')

@section('article_class', 'item-editor-page')

@section('content')
<div class="title-block">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.assets')}}">Imagens e Vídeos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>
    <span class="sparkline bar" data-type="bar"></span>
</div>
@include('admin.include.forms.messages')
<form id="form-asset" name="form-parceiro" v-on:submit.prevent enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="card card-block">
        @include('admin.assets.shared.form-edit')
        {{-- @include('admin.assets.shared.form-medias') --}}
        @include('admin.include.forms.edit',['save_method' => 'editAsset()', 'route_back' => 'admin.assets'])
    </div>
</form>
{{-- @include('admin.include.modal-media') --}}

@endsection

@section('script')
<script> var id = '{{$id}}'; </script>
<script src="{{ url('js/admin/mixins/errors.js') }}"></script>
<script src="{{ url('js/admin/mixins/edit.js') }}"></script>
<script src="{{ url('js/admin/mixins/asset.js') }}"></script>
<script src="{{ url('js/admin/mixins/messages.js') }}"></script>
<script src="{{ url('js/admin/mixins/media.js') }}"></script>
<script src="{{ url('js/admin/assets-edit.js') }}"></script>
@endsection