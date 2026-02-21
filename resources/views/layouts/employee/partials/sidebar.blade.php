<!-- BEGIN #sidebar -->
<div id="sidebar" class="app-sidebar">
	<div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
		<div class="menu">
			<div class="menu-header">Employee</div>

			<div class="menu-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }} mb-1">
				<a href="{{ route('employee.dashboard') }}" class="menu-link">
					<span class="menu-icon"><i class="fa fa-home fa-fw"></i></span>
					<span class="menu-text">Dashboard</span>
				</a>
			</div>

			<div class="menu-item {{ request()->routeIs('employee.profile') ? 'active' : '' }} mb-1">
				<a href="{{ route('employee.profile') }}" class="menu-link">
					<span class="menu-icon"><i class="fa fa-user fa-fw"></i></span>
					<span class="menu-text">Profile</span>
				</a>
			</div>

			<div class="menu-item {{ request()->routeIs('employee.payslips.*') ? 'active' : '' }} mb-1">
				<a href="{{ route('employee.payslips.list') }}" class="menu-link">
					<span class="menu-icon"><i class="fa fa-file-invoice-dollar fa-fw"></i></span>
					<span class="menu-text">Payslips</span>
				</a>
			</div>

			<div class="menu-item {{ request()->routeIs('employee.leaves.*') ? 'active' : '' }} mb-1">
				<a href="{{ route('employee.leaves.list') }}" class="menu-link">
					<span class="menu-icon"><i class="fa fa-calendar-alt fa-fw"></i></span>
					<span class="menu-text">Leaves</span>
				</a>
			</div>

			<div class="menu-item {{ request()->routeIs('employee.claims.*') ? 'active' : '' }} mb-1">
				<a href="{{ route('employee.claims.list') }}" class="menu-link">
					<span class="menu-icon"><i class="fa fa-receipt fa-fw"></i></span>
					<span class="menu-text">Claims</span>
				</a>
			</div>

			<div class="menu-item {{ request()->routeIs('employee.attendance.*') ? 'active' : '' }} mb-1">
				<a href="{{ route('employee.attendance.list') }}" class="menu-link">
					<span class="menu-icon"><i class="fa fa-clock fa-fw"></i></span>
					<span class="menu-text">Attendance</span>
				</a>
			</div>

			<div class="menu-item mb-1">
				<a href="{{ route('employee.logout') }}" class="menu-link">
					<span class="menu-icon"><i class="fa fa-sign-out-alt fa-fw"></i></span>
					<span class="menu-text">Logout</span>
				</a>
			</div>
		</div>
	</div>
</div>
<!-- END #sidebar -->

<button class="app-sidebar-mobile-backdrop" data-toggle-target=".app" data-toggle-class="app-sidebar-mobile-toggled"></button>
