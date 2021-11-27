<aside class="sidebar sidebar-fixed">
	<div class="sidebar-container">
		<div class="sidebar-header">
			<div class="brand">
				<div class="logo e-tools">e-Tools Digital Connections</div>
				| Admin
			</div>
		</div>
		<nav class="menu">
			<ul class="sidebar-menu metismenu" id="sidebar-menu">
				@if(isset($modulos))
					@foreach($modulos as $key => $modulo)
						<li class="{{$modulo->id == $current_role ? 'active' : ''}}">
							<a href="{{route($modulo->modulo_url)}}">
								<i class="fa {{$modulo->icone}}"></i>
								{{$modulo->nome}}
							</a>
						</li>
					@endforeach
				@endif
			</ul>
		</nav>
	</div>
</aside>
<div class="sidebar-overlay" id="sidebar-overlay"></div>
<div class="sidebar-mobile-menu-handle" id="sidebar-mobile-menu-handle"></div>
<div class="mobile-menu-handle"></div>
