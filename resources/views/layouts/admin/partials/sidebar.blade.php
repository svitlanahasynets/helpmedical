<div class="page-sidebar-wrapper">
	<div class="page-sidebar navbar-collapse collapse">
		<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
			<li class="sidebar-toggler-wrapper">
				<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				<div class="sidebar-toggler">
				</div>
				<!-- END SIDEBAR TOGGLER BUTTON -->
			</li>
			@foreach ($main_menu as $key => $menu)
			<li class="{{ ($menu['active']) ? ('active') : ('') }} {{ ($menu['active']) && (isset($menu['children'])) ? ('open') : ('') }}">
				<a href="{{ $menu['url'] }}" class="{{ ($menu['active']) && (!isset($menu['children'])) ? ('active') : ('') }} {{ ($menu['active']) && (isset($menu['children'])) ? ('open') : ('') }}">
					<i class="icon-{{ $menu['icon'] }}"></i>
					<span class="title">{{ $menu['title'] }}</span>
					@if (isset($menu['children']))
					<span class="arrow {{ ($menu['active']) ? ('open') : ('') }}"></span>
					@endif
					@if (isset($menu['active']))
					<span class="selected"></span>
					@endif
				</a>
				@if (isset($menu['children']))
				<ul class="sub-menu">
					@foreach ($menu['children'] as $subkey => $submenu)
					<li>
						<a href="{{ $submenu['url'] }}"  class="{{ ($submenu['active']) ? ('active') : ('') }}">
							{{ $submenu['title'] }}
						</a>
					</li>
					@endforeach
				</ul>
				@endif
			</li>
			@endforeach
		</ul>
		<!-- END SIDEBAR MENU -->
	</div>
</div>