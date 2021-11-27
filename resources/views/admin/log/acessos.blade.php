@extends('layouts.admin')

@section('article_class','items-list-page')

@section('content')
    <div class="title-block">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Logs de acesso</li>
            </ol>
        </nav>
	</div>
	@include('admin.include.search-only')
    <div class="card itens">
        {{-- <div class="row" v-if="carregando">
            @include('admin.include.loader')
        </div> --}}
        <ul class="item-list striped">
            <li class="item item-list-header">
                <div class="item-row">
                    <div class="item-col item-col-header">
                        <div><span>Usuário</span></div>
                    </div>
                    <div class="item-col item-col-header">
                        <div><span>IP</span></div>
                    </div>
                    <div class="item-col item-col-header">
                        <div class="no-overflow"><span>Data</span></div>
                    </div>
                </div>
            </li>
            <li class="item" v-for="(registro, index ) in registros.data">
                <div class="item-row">
                    <div class="item-col pull-left ">
                        <div class="item-heading">Usuário</div>
                        <div>
                            <h4 class="item-title">@{{registro.administrador.nome}}</h4>
                        </div>
                    </div>
                    <div class="item-col pull-left ">
                        <div class="item-heading">Ip</div>
                        <div>
                            <h4 class="item-title">@{{registro.ip}}</h4>
                        </div>
                    </div>
                    <div class="item-col">
                        <div class="item-heading">Data</div>
                        <div class="no-overflow"> @{{formatDate(registro.data)}} </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@endsection
@section('script')
    <script src="{{ url('js/admin/log.js') }}"></script>
@endsection
