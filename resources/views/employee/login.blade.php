<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>{{ env('APP_NAME','Mohaaseb') }} | Employee Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="{{ asset('hud/assets/css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('hud/assets/css/app.min.css') }}" rel="stylesheet">
</head>
<body class='pace-top'>
    <div id="app" class="app app-full-height app-without-header">
        <div class="login">
            <div class="login-content">
                <form action="{{ route('employee.postLogin') }}" method="POST" name="employee_login_form">
                    @csrf
                    <h1 class="text-center">Employee Sign In</h1>
                    <div class="text-inverse text-opacity-50 text-center mb-4">
                        Please sign in to view your HRM details.
                    </div>
                    @if(session('error'))
                        <div class="alert alert-warning text-center">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="text" name="email" class="form-control form-control-lg bg-inverse bg-opacity-5" value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control form-control-lg bg-inverse bg-opacity-5" value="">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-outline-theme btn-lg d-block w-100 fw-500 mb-3">Sign In</button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('hud/assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('hud/assets/js/app.min.js') }}"></script>
</body>
</html>
