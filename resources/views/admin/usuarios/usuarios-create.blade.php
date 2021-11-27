<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 09/02/2021
 * Time: 12:51
 */
?>
@extends('layouts.admin')

@section('article_class', 'item-editor-page')

@section('content')
	<div class="title-block">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
				<li class="breadcrumb-item"><a href="{{route('admin.usuarios')}}">Usuarios</a></li>
				<li class="breadcrumb-item active" aria-current="page">Adicionar</li>
			</ol>
		</nav>
		<span class="sparkline bar" data-type="bar"></span>
	</div>
	@include('admin.include.forms.messages')
	<form id="form-usuario" name="form-usuario" v-on:submit.prevent enctype="multipart/form-data">
		{{csrf_field()}}
		<div class="card card-block">
			@include('admin.usuarios.shared.form')
			@include('admin.include.forms.save',['save_method' => 'createUsuario()', 'route_back' => 'admin.usuarios'])
		</div>
	</form>
@endsection

@section('script')
	<script src="{{ url('js/admin/mixins/errors.js') }}"></script>
	<script src="{{ url('js/admin/mixins/create.js') }}"></script>
	<script src="{{ url('js/admin/mixins/usuario.js') }}"></script>
	<script src="{{ url('js/admin/mixins/messages.js') }}"></script>
	<script src="{{ url('js/admin/mixins/media.js') }}"></script>
	<script src="{{ url('js/admin/usuarios-create.js') }}"></script>
@endsection
