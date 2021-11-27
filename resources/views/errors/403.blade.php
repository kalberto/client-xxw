@extends('layouts.admin')

@section('article_class', 'error-404-page')

@section('content')
    <section class="section">
        <div class="error-card">
            <div class="error-title-block">
                <h1 class="error-title">403</h1>
                <h2 class="error-sub-title"> Desculpe, você não possui permissão para visualizar esta página! </h2>
            </div>
            <div class="error-container">
                <p> Se realmente necessita acessar esta página, entre em contato com um administrador. </p>
                <br>
                <a class="btn btn-primary" href="{{route('admin')}}">
                    <i class="fa fa-angle-left"></i> Voltar ao início
                </a>
            </div>
        </div>
    </section>
@endsection
