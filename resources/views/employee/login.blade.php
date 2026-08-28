<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <script>
        (function() {
            var theme = localStorage.getItem('admin-theme') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    <meta charset="utf-8">
    <title>{{ env('APP_NAME','Mohaaseb') }} | Employee Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="{{ asset('hud/assets/css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('hud/assets/css/app.min.css') }}" rel="stylesheet">

    <style>
        [data-bs-theme="light"] .login-content {
            background: rgba(255,255,255,0.95);
            color: #1a1a2e;
        }
        [data-bs-theme="light"] .login-content .form-label { color: #333; }
        [data-bs-theme="light"] .login-content h1 { color: #1a1a2e; }
        [data-bs-theme="light"] .login-content .text-inverse { color: #555 !important; }
        [data-bs-theme="light"] .form-control.bg-inverse { background: #f5f5f5 !important; color: #333 !important; }
        [data-bs-theme="dark"] .login-content { color: #ffffff; }
        [data-bs-theme="dark"] .login-content .form-label { color: #e0e0e0; }
    </style>
</head>
<body class='pace-top'>
    <div id="app" class="app app-full-height app-without-header">
        <div class="login">
            <div class="login-content position-relative">
                <button type="button" class="btn btn-sm btn-outline-theme position-absolute top-0 end-0 m-3"
                        onclick="toggleLoginTheme()" id="themeToggleBtn">
                    <i class="fa fa-moon" id="themeIcon"></i>
                </button>
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

    <script>
        function toggleLoginTheme() {
            var html = document.documentElement;
            var current = html.getAttribute('data-bs-theme');
            var next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', next);
            localStorage.setItem('admin-theme', next);
            document.getElementById('themeIcon').className = next === 'dark' ? 'fa fa-moon' : 'fa fa-sun';
        }
        document.addEventListener('DOMContentLoaded', function() {
            var saved = localStorage.getItem('admin-theme') || 'dark';
            document.getElementById('themeIcon').className = saved === 'dark' ? 'fa fa-moon' : 'fa fa-sun';
        });
    </script>
</body>
</html>
