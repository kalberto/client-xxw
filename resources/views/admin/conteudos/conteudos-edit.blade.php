@extends('layouts.admin')

@section('article_class', 'item-editor-page')

@section('content')
<div class="title-block">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.conteudos')}}">Conteudos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>
    <span class="sparkline bar" data-type="bar"></span>
</div>
<div class="card card-success" v-cloak v-if="success && successFile">
	<div class="card-header">
		<div class="header-block">
			<p class="title"> Sucesso</p>
		</div>
	</div>
	<div class="card-block">
		<p>@{{ successMessage }}</p>
	</div>
</div>
<div class="card card-danger" v-cloak v-if="error || errorFile">
	<div class="card-header">
		<div class="header-block">
			<p class="title text-white"> Erro</p>
		</div>
	</div>
	<div class="card-block">
		<p>@{{ errorMessage }}</p>
	</div>
</div>
<form id="form-conteudo" name="form-conteudo" v-on:submit.prevent enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="card card-block">
        @include('admin.conteudos.shared.form')
	    <div class="subtitle-block"></div>
	    <div class="form-group row">
		    <div class="col-sm-1 col-sm-offset-2">
			    <div v-if="sending  && sendingFile">@{{sendingMessage}}</div>
			    <button v-else type="submit" class="btn btn-primary" @click="editConteudo()"> Atualizar </button>
		    </div>
		    <div class="col-sm-1">
			    <button type="button" class="btn btn-secondary-outline" style="color:black" @click="backMethod" v-if="!sending && !sendingFile"> Voltar </button>

		    </div>
	    </div>
    </div>
</form>
@endsection

@section('script')
    <script> var id = '{{$id}}'; </script>
    <script src="{{ url('js/admin/mixins/errors.js') }}"></script>
    <script src="{{ url('js/admin/mixins/edit.js') }}"></script>
    <script src="{{ url('js/admin/mixins/conteudo.js') }}"></script>
    <script src="{{ url('js/admin/mixins/messages.js') }}"></script>
    <script src="{{ url('js/admin/mixins/media.js') }}"></script>
    <script src="{{ url('js/admin/conteudos-edit.js') }}"></script>
@endsection
