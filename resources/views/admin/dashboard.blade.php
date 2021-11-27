@extends('layouts.admin')
@section('content')
<div class="title-block">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active" aria-current="page"><h5>Dashboard</h5></li>
		</ol>
	</nav>
</div>
<div class="dashboard-page">
	<div class="card stats" data-exclude="xs">
		<div class="card-block">
			<div class="title-block">
				<h4 class="title"> Estatísticas </h4>
				<p class="title-description"> Métricas para o site
					<a href="https://www.xxw-canal.com.br" target="_blank"> xxw </a>
				</p>
			</div>
			<div class="row row-sm stats-container">
				<div class="col-6 col-sm-12 stat-col">
					<div class="stat-icon">
						<i class="fa fa-users"></i>
					</div>
					<div class="stat">
						<div class="value"> @{{ totalUsuarios }} </div>
						<div class="name"> Total de Usuarios Ativos </div>
					</div>
					<div class="progress stat-progress">
						<div class="progress-bar" :style="'width: '+porcentagemUsuarios + '%;'"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<section class="section">
	<div class="row sameheight-container">
		@if(isset($modulos))
			@foreach($modulos as $key => $modulo)
				@if($modulo->id != 1)
					<div class="col col-12 col-sm-12 col-md-6 col-xl-3">
						<div class="card sameheight-item" data-exclude="xs">
							<div class="card-block">
								<div class="title-block">
									<h4 class="title">{{$modulo->nome}}</h4>
								</div>
								<div class="card-container">
									<a href="{{route($modulo->modulo_url)}}">
										Acessar
									</a>
								</div>
							</div>
						</div>
					</div>
				@endif
			@endforeach
		@endif
	</div>
</section>
<modal-component></modal-component>
@endsection
@section('script')
<script src="{{ url('js/admin/dashboard.js') }}"></script>
@endsection
