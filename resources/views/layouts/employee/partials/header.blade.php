<!-- BEGIN #header -->
<div id="header" class="app-header">
	<div class="desktop-toggler">
		<button type="button" class="menu-toggler" data-toggle-class="app-sidebar-collapsed" data-dismiss-class="app-sidebar-toggled" data-toggle-target=".app">
			<span class="bar"></span>
			<span class="bar"></span>
			<span class="bar"></span>
		</button>
	</div>

	<div class="mobile-toggler">
		<button type="button" class="menu-toggler" data-toggle-class="app-sidebar-mobile-toggled" data-toggle-target=".app">
			<span class="bar"></span>
			<span class="bar"></span>
			<span class="bar"></span>
		</button>
	</div>

	<div class="brand">
		<a href="{{ route('employee.dashboard') }}" class="brand-logo">
			<span class="navbar-brand d-flex align-items-center me-auto px-0">
				<img src="{{ tenantSetting('logo', asset('mohaaseb_en_dark.png')) }}" alt="Logo" class="d-inline-block" height="50">
			</span>
			<span class="brand-text">{{ tenantSetting('business_name', tenant()->name) }}</span>
		</a>
	</div>

	<div class="menu">
		<div class="menu-item dropdown dropdown-mobile-full">
			<a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
				<div class="menu-img online">
					<img src="{{ asset('hud/assets/img/user/profile.jpg') }}" alt="Profile" height="60">
				</div>
			</a>
			<div class="dropdown-menu me-lg-3 fs-11px mt-1 dropdown-menu-end dropdown-menu-lg-{{ $__locale == 'en' ? 'end' : 'start' }}">
				<h6 class="dropdown-header">Employee: {{ employee()?->name }}</h6>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item d-flex align-items-center" href="{{ route('employee.profile') }}">Profile <i class="bi bi-person-circle ms-auto text-theme fs-16px my-n1"></i></a>
				<a class="dropdown-item d-flex align-items-center" href="{{ route('employee.logout') }}">Logout <i class="bi bi-toggle-off ms-auto text-theme fs-16px my-n1"></i></a>
			</div>
		</div>
	</div>
</div>
<!-- END #header -->
