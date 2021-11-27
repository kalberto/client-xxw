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
                        <small>Informe uma nova senha.</small>
                    </p>
                    <form v-on:submit.prevent="resetPass" action="{{route('password.new')}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="password">Nova senha</label>
                            <input type="password" class="form-control underlined" name="password" id="password" placeholder="Senha" v-model="form.password" required>
                        </div>
                        <div class="form-group">
                            <label for="re_password">Confirme sua nova senha</label>
                            <input type="password" class="form-control underlined" name="re_password" id="re_password" placeholder="Senha" v-model="form.re_password" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">Enviar</button>
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
                        <a href="{{route('auth.forgot')}}" class="btn btn-block btn-primary">Recuperar senha</a>
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
    <script> var token='{{$token}}';</script>
    <script src="{{ url('js/admin/new.js') }}"></script>
@endsection
