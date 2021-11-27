@extends('layouts.admin')

@section('article_class', 'items-list-page')

@section('content')
    <div class="title-block">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Imagens e Vídeos</li>
            </ol>
        </nav>
    </div>
    @include('admin.include.search', ['route' => 'admin.assets.adicionar'])
    @include('admin.include.forms.messages')
    <div class="card items">
        <ul class="item-list striped" >
            <li class="item item-list-header">
                <div class="item-row">
                    <div class="item-col item-col-header fixed item-col-img md">
                        <div><span>Media</span></div>
                    </div>
                    <div class="item-col item-col-header ">
                        <div><span>Arquivo</span></div>
                    </div>
                    <div class="item-col item-col-header ">
                        <div><span>Relação</span></div>
                    </div>
                    <div class="item-col item-col-header fixed">
                        <div><span>Ações</span></div>
                    </div>
                </div>
            </li>
            <li class="item" v-for="(registro, index) in registros.data">
                <div class="item-row">
                    <div class="item-col fixed item-col-img md">
                        <a v-bind:href="registro.link" v-bind:title="registro.nome">
                            <img class="item-img rounded" :src="base_url + '/' + registro.media_root.path + registro.media_root.media_resizes[0].path + (registro.tipo == 1 ? (registro.file) : (registro.thumbnail))" v-if="registro.video_is_link != '1'">
                            <div class="item-img rounded" style="background-image: url({{url('/images/admin/656f77.png')}})" v-else></div>
                        </a>
                    </div>
                    <div class="item-col pull-left ">
                        <div class="item-heading">file</div>
                        <div>
                            <a v-bind:href="registro.link" v-bind:title="registro.file">
                                <h4 class="item-title"> @{{registro.file}} </h4>
                            </a>
                        </div>
                    </div>
                    <div class="item-col pull-left ">
                        <div class="item-heading">Relação</div>
                        <div>
                            <h4 class="item-title"> @{{registro.conteudo ? registro.conteudo.nome.length <= 65 ? registro.conteudo.nome : registro.conteudo.nome.substr(0,65)+"..." : 'Sem Relação'}} </h4>
                        </div>
                    </div>
                    <div class="item-col fixed item-col-actions-normal">
                        <ul class="item-actions-list">
                            <li>
                                <a class="edit" v-bind:href="registro.link" v-bind:title="registro.nome"><i class="fa fa-pencil"></i></a>
                            </li>
                            <li>
                                <a class="remove" @click="clickDelete(registro.id, index)" v-bind:title="registro.id"><i class="fa fa-trash-o "></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
        @include('admin.include.modal-delete')
    </div>
    @include('admin.include.pagination', ['paginador' => 'registro'])
@endsection
@section('script')
    <script src="{{ url('js/admin/mixins/main.js') }}"></script>
    <script src="{{ url('js/admin/mixins/messages.js') }}"></script>
    <script src="{{ url('js/admin/mixins/asset.js') }}"></script>
    <script src="{{ url('js/admin/mixins/listing.js') }}"></script>
    <script src="{{ url('js/admin/mixins/delete.js') }}"></script>
    <script src="{{ url('js/admin/assets.js') }}"></script>
@endsection
