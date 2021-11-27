<header class="header header-fixed">
    <div class="header-block header-block-collapse d-lg-none d-xl-none">
        <button class="collapse-btn" id="sidebar-collapse-btn">
            <i class="fa fa-bars"></i>
        </button>
    </div>
    {{-- <div class="header-block header-block-search">
        <form role="search">
            <div class="input-container">
                <i class="fa fa-search"></i>
                <input type="search" placeholder="Search">
                <div class="underline"></div>
            </div>
        </form>
    </div> --}}
    <div class="header-block header-block-nav">
        <ul class="nav-profile">
            <li class="ultimo-acesso">
                <span>
                    Último login: {{date('d/m/Y',strtotime(Auth::guard('admin')->user()->ultimo_acesso))}}
                </span>
            </li>
            <li class="profile dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    {{-- @if(Auth::user()->media()->first())
                        <img class="item-img rounded" src="{{url('').'/upload/administradores/small/'.Auth::user()->media()->first()->file}}" width="32px">
                    @else
                        <div class="img" style="background-image: url('{{url('images/admin/photo_admin.jpg')}}')"></div>
                    @endif --}}
                    <span class="name"> {{Auth::guard('admin')->user()->nome}} </span>
                </a>
                <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                    <a class="dropdown-item" href="{{route('admin.administradores.editar',Auth::guard('admin')->user()->id)}}">
                        <i class="fa fa-user icon"></i>
                        Perfil
                    </a>
                    <a class="dropdown-item" href="{{route('admin.log')}}">
                        <i class="fa fa-bell icon"></i>
                        Log de acessos
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('logout')}}">
                        <i class="fa fa-power-off icon"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
    @yield('header')
</header>
