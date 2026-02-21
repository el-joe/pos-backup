<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Regal Admin</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{ asset('template/') }}/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('template/') }}/vendors/feather/feather.css">
    <link rel="stylesheet" href="{{ asset('template/') }}/vendors/base/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('template/') }}/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('template/') }}/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
                <div class="row flex-grow">
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <div class="auth-form-transparent text-left p-3">
                            <div class="brand-logo">
                                <img src="{{ asset('template/') }}/images/logo-dark.svg" alt="logo">
                            </div>
                            @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{session('success')}}
                            </div>
                            @endif
                            <h4>New here?</h4>
                            <h6 class="font-weight-light">Join us today! It takes only few steps</h6>
                            <form class="pt-3" action="{{ route('register-domain') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Business Name</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-account-outline text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg border-left-0" name="id" value="{{old('id')}}" placeholder="Business Name" required>
                                    </div>
                                    @error('id')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Domain Type</label>
                                    <div class="d-flex align-items-center gap-3" style="gap: 1rem;">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="domain_mode" value="subdomain" id="mode_subdomain" {{ old('domain_mode', 'subdomain') === 'subdomain' ? 'checked' : '' }}> Subdomain
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="domain_mode" value="domain" id="mode_domain" {{ old('domain_mode') === 'domain' ? 'checked' : '' }}> Custom Domain
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" id="group_subdomain">
                                    <label>Subdomain</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-laptop text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg border-left-0" id="subdomain_input" name="subdomain" value="{{ old('subdomain') }}" placeholder="yourname">
                                    </div>
                                    <small class="form-text text-muted mt-1" id="domain_preview">Will be: —</small>
                                    @error('domain')
                                    <small class="text-danger d-block">{{$message}}</small>
                                    @enderror
                                </div>

                                <div class="form-group" id="group_domain" style="display:none;">
                                    <label>Custom Domain</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-laptop text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg border-left-0" id="domain_text_input" name="domain" placeholder="example.com" value="{{ old('domain') }}">
                                    </div>
                                    @error('domain')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <input type="hidden" name="domain" id="final_domain_input" value="{{ old('domain') }}">
                                <div class="form-group">
                                    <label>Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-email-outline text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="email" class="form-control form-control-lg border-left-0" value="{{old('email')}}"  name="email" placeholder="Email" required>
                                    </div>
                                    @error('email')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-phone-outline text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg border-left-0" value="{{old('phone')}}"  name="phone" placeholder="Phone" required>
                                    </div>
                                    @error('phone')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                {{-- countries list --}}
                                <div class="form-group">
                                    <label>Country</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-earth text-primary"></i>
                                            </span>
                                        </div>
                                        <select class="form-control form-control-lg border-left-0" name="country_id" required>
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('country_id')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-lock-outline text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control form-control-lg border-left-0" name="password" id="exampleInputPassword" placeholder="******" required>
                                    </div>
                                    @error('password')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Password Confirmation</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-lock-outline text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control form-control-lg border-left-0" name="password_confirmation" id="exampleInputPassword1" placeholder="******" required>
                                    </div>
                                    @error('password_confirmation')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" required>
                                        <label class="form-check-label text-muted">
                                            I agree to all <a href="/terms" target="_blank">Terms & Conditions</a>
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-block btn-info btn-lg font-weight-medium auth-form-btn" type="submit" >SIGN UP</button>
                                </div>
                                {{-- <div class="text-center mt-4 font-weight-light">
                                    Already have an account? <a href="/login" class="text-primary">Login</a>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 register-half-bg d-flex flex-row">
                        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy;
                            {{ date('Y') }} All rights reserved.</p>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- base:js -->
    <script src="{{ asset('template/') }}/vendors/base/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="{{ asset('template/') }}/js/off-canvas.js"></script>
    <script src="{{ asset('template/') }}/js/hoverable-collapse.js"></script>
    <script src="{{ asset('template/') }}/js/template.js"></script>
    <!-- endinject -->
    <script>
        (function() {
            const form = document.querySelector('form[action*="register-domain"]') || document.querySelector('form.pt-3');
            if (!form) return;

            const modeRadios = document.querySelectorAll('input[name="domain_mode"]');
            const groupSub = document.getElementById('group_subdomain');
            const groupDom = document.getElementById('group_domain');
            const subInput = document.getElementById('subdomain_input');
            const baseSelect = document.getElementById('base_domain_select');
            const domainText = document.getElementById('domain_text_input');
            const finalInput = document.getElementById('final_domain_input');
            const preview = document.getElementById('domain_preview');

            function getMode() {
                const checked = Array.from(modeRadios).find(r => r.checked);
                return checked ? checked.value : 'subdomain';
            }

            function sanitizeSubdomain(v) {
                return (v || '')
                    .toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^a-z0-9-]/g, '')
                    .replace(/^-+|-+$/g, '')
                    .substring(0, 63);
            }

            function updateView() {
                const mode = getMode();
                if (mode === 'domain') {
                    groupDom.style.display = '';
                    groupSub.style.display = 'none';
                } else {
                    groupDom.style.display = 'none';
                    groupSub.style.display = '';
                }
                composeFinal();
            }

            function composeFinal() {
                const mode = getMode();
                if (mode === 'domain') {
                    const value = (domainText && domainText.value) ? domainText.value.trim() : '';
                    finalInput.value = value;
                    if (preview) preview.textContent = value ? `Will be: ${value}` : 'Will be: —';
                } else {
                    if (!subInput) return;
                    const sub = sanitizeSubdomain(subInput.value);
                    if (subInput.value !== sub) subInput.value = sub; // keep UI in sync
                    const base = '{{ str_replace('https://', '', str_replace('http://', '', url('/'))) }}';
                    const composed = sub && base ? `${sub}.${base}` : '';
                    finalInput.value = composed;
                    if (preview) preview.textContent = composed ? `Will be: ${composed}` : 'Will be: —';
                }
            }

            modeRadios.forEach(r => r.addEventListener('change', updateView));
            if (subInput) subInput.addEventListener('input', composeFinal);
            if (baseSelect) baseSelect.addEventListener('change', composeFinal);
            if (domainText) domainText.addEventListener('input', composeFinal);
            form.addEventListener('submit', composeFinal);

            // Initial state
            updateView();
        })();
    </script>
</body>

</html>
