@extends('layouts.admin')

@section('content')
	<div class="auth">
		<div class="auth-container">
			<div class="card">
				<header class="auth-header">
					@if(isset($configuracao->nome_app) && $configuracao->nome_app != null)
						<h1 class="auth-title">Admin | {{$configuracao->nome_app}}</h1>
					@else
						<h1 class="auth-title">Admin | {{config('app.name')}}</h1>
					@endif
				</header>
				<div class="auth-content">
					<form id="login-form" action="{{route('login')}}" method="POST">
						{{csrf_field()}}
						<div class="form-group">
							<label for="username">Usuário</label>
							<input type="email" class="form-control underlined" name="email" id="username" placeholder="admin@admin.com" value="{{ old('email') }}" required> </div>
							@if ($errors->has('email'))
								<span class="help-block">
                                	<strong>{{ $errors->first('email') }}</strong>
                            	</span>
							@endif
						<div class="form-group">
							<label for="password">Senha</label>
							<input type="password" class="form-control underlined" name="password" id="password" placeholder="Sua Senha" required> </div>
							@if ($errors->has('password'))
								<span class="help-block">
                                	<strong>{{ $errors->first('password') }}</strong>
                            	</span>
							@endif
						<div class="form-group">
							<a href="{{route('auth.forgot')}}" class="forgot-btn pull-right">Esqueceu sua senha?</a>
						</div>
						<div class="form-group">
							<button class="btn btn-block btn-primary">Login</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
