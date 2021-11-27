@extends('layouts.admin')

@section('content')
    <div class="auth">
        <div class="auth-container">
            <div class="card">
                <header class="auth-header">
                    <h1 class="auth-title">Admin | e-Tools</h1>
                </header>
                <div class="auth-content" v-if="not_sended">
                    <p class="text-center">Recuperação de senha</p>
                    <p class="text-muted text-center">
                        <small>Informe seu e-mail e enviaremos as instruções necessárias.</small>
                    </p>
                    <form v-on:submit.prevent="resetPass" action="{{route('auth.email')}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control underlined" name="email" id="email" placeholder="Email" v-model="form.email" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">Enviar</button>
						</div>
						<div class="form-group">
                            <a href="{{route('login')}}" style="text-decoration:none">
                                <button type="button" class="btn btn-secondary btn-sm btn-block"> Voltar </button>
                            </a>
                        </div>
                    </form>
                </div>
                <div class="auth-content" v-else>
                    <p class="text-center">Recuperação de senha</p>
                    <p class="text-muted text-center">
                        <small>@{{ msg }}</small>
                    </p>
                    <div class="form-group" v-if="error">
                        <button class="btn btn-block btn-primary" v-on:click="tryAgain">Tentar novamente</button>
                    </div>
                    <div class="form-group" v-else>
                        <a href="{{route('auth.login')}}" class="btn btn-block btn-primary">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ url('js/admin/reset.js') }}"></script>
@endsection
