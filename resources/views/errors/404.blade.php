@extends('layouts.admin')

@section('article_class', 'error-404-page')

@section('content')
    <section class="section" style="margin-right: 270px;">
        <div class="error-card">
            <div class="error-title-block">
                <h1 class="error-title">404</h1>
                <h2 class="error-sub-title"> Página não encontrada :( </h2>
            </div>
            <div class="error-container">
                <p>Parece que a página não existe.</p>
				<br>
				<button onclick="goBack()" class="btn green accent-4">Voltar</button>
                <a class="btn btn-primary" href="{{route('admin')}}">
                    <i class="fa fa-angle-left"></i> Voltar ao início
                </a>
            </div>
        </div>
    </section>
@endsection

<script type="text/javascript">
	function goBack() {
		window.history.back();
	}
</script>

