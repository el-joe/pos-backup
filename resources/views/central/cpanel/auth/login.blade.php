<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>{{ env('APP_NAME') }} | Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- ================== BEGIN core-css ================== -->
	<link href="{{ asset('hud/assets/') }}/css/vendor.min.css" rel="stylesheet">
	<link href="{{ asset('hud/assets/') }}/css/app.min.css" rel="stylesheet">
	<!-- ================== END core-css ================== -->

</head>
<body class='pace-top'>
	<!-- BEGIN #app -->
	<div id="app" class="app app-full-height app-without-header">
		<!-- BEGIN login -->
		<div class="login">
			<!-- BEGIN login-content -->
			<div class="login-content">
				<form action="{{ route('cpanel.postLogin') }}" method="POST" name="login_form">
					@csrf
					<h1 class="text-center">Sign In</h1>
					<div class="mb-3">
						<label class="form-label">Email Address <span class="text-danger">*</span></label>
						<input type="text" class="form-control form-control-lg bg-inverse bg-opacity-5" name="email" placeholder="Enter your email" required>
					</div>
					<div class="mb-3">
						<div class="d-flex">
							<label class="form-label">Password <span class="text-danger">*</span></label>
						</div>
						<input type="password" class="form-control form-control-lg bg-inverse bg-opacity-5" name="password" placeholder="Enter your password" required>
					</div>
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

					<button type="submit" class="btn btn-outline-theme btn-lg d-block w-100 fw-500 mb-3">Sign In</button>
				</form>
			</div>
			<!-- END login-content -->
		</div>
		<!-- END login -->
		<!-- BEGIN btn-scroll-top -->
		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
		<!-- END btn-scroll-top -->
	</div>
	<!-- END #app -->

	<!-- ================== BEGIN core-js ================== -->
	<script src="{{ asset('hud/assets/') }}/js/vendor.min.js"></script>
	<script src="{{ asset('hud/assets/') }}/js/app.min.js"></script>
	<!-- ================== END core-js ================== -->


</body>
</html>
