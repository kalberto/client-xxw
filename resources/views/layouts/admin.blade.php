<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
		@if(isset($configuracao) && $configuracao != null)
			<title>ADMIN | {{$configuracao->nome_app}}</title>
		@else
			<title>ADMIN | {{config('app.name')}}</title>
		@endif
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" type="image/png" href="{{url('images/admin/icon-etools.png')}}" sizes="32x10">
        <link rel="stylesheet" href="{{url('css/admin/vendor.css')}}">
        {{-- <link rel="stylesheet" href="{{url('css/admin/app.css')}}"> --}}
        <link rel="stylesheet" href="{{url('css/admin/app-seagreen.css')}}">
		<link rel="stylesheet" href="{{url('css/admin/app.custom.css')}}">
		<link rel="stylesheet" href="{{url('css/admin/datetimepicker.css')}}">
        @yield('css')
    </head>
    <body>
        <div class="main-wrapper">
            <div id="app" class="app">
                @if(!Auth::guard('admin')->guest())
                    @include('admin.include.header')
                    @include('admin.include.sidebar')
                    <article class="content @yield('article_class')">
                        @yield('content')
                    </article>
                    @include('admin.include.footer')
                @else
                    @yield('content')
                @endif
            </div>
        </div>

        <!-- Reference block for JS -->
        <div class="ref" id="ref">
            <div class="color-primary"></div>
            <div class="chart">
                <div class="color-primary"></div>
                <div class="color-secondary"></div>
            </div>
        </div>

        <script>var base_url = '{{url('')}}';</script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.3"></script>
        <script src="{{ url('js/admin/app.js') }}"></script>
        <script src="{{ url('js/admin/api.js') }}"></script>
        <script src="{{url('js/admin/mixins/main.js')}}"></script>
        <script src="{{ url('js/admin/modular_admin_vendor.js') }}"></script>
		<script src="{{ url('js/admin/modular_admin_app.js') }}"></script>
		<script src="{{ url('js/admin/datetimepicker.js') }}"></script>
        {{-- <script src="{{ url('js/admin/vue-quill-editor.js') }}"></script> --}}
        @yield('script')
    </body>
    <!--
        ModularAdmin by ModularCode

        LICENSE
            The MIT License (MIT)
            Copyright (c) 2016 ModularCode
            https://github.com/modularcode

            Permission is hereby granted, free of charge, to any person obtaining a copy
            of this software and associated documentation files (the "Software"), to deal
            in the Software without restriction, including without limitation the rights
            to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
            copies of the Software, and to permit persons to whom the Software is
            furnished to do so, subject to the following conditions:

            The above copyright notice and this permission notice shall be included in all
            copies or substantial portions of the Software.

            THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
            IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
            FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
            AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
            LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
            OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
            SOFTWARE.

        <=========>

        Modificado por E-Tools Digital Connections
        http://etools.com.br
    -->
</html>
