@extends('layouts.admin')

@section('article_class', 'item-editor-page')

@section('content')
<div class="title-block">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.faqs')}}">Faqs</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>
    <span class="sparkline bar" data-type="bar"></span>
</div>
@include('admin.include.forms.messages')
<form id="form-faqs" name="form-faqs" v-on:submit.prevent enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="card card-block">
        @include('admin.faqs.shared.form',['edit' => true])
        @include('admin.include.forms.edit',['save_method' => 'editRegistro()', 'route_back' => 'admin.faqs'])
    </div>
</form>

@endsection

@section('script')
<script> var id = '{{$id}}'; </script>
<script src="{{ url('js/admin/mixins/errors.js') }}"></script>
<script src="{{ url('js/admin/mixins/edit.js') }}"></script>
<script src="{{ url('js/admin/mixins/faq.js') }}"></script>
<script src="{{ url('js/admin/mixins/messages.js') }}"></script>
<script src="{{ url('js/admin/faqs-edit.js') }}"></script>
@endsection
