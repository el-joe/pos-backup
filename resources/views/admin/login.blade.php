<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Regal Admin</title>
  <!-- base:css -->
  <link rel="stylesheet" href="{{ asset('template/vendors') }}/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="{{ asset('template/vendors') }}/feather/feather.css">
  <link rel="stylesheet" href="{{ asset('template/vendors') }}/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('template') }}/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('template') }}/images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="{{ asset('template/') }}/images/logo-dark.svg" alt="logo">
              </div>
              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form class="pt-3" action="{{ route('admin.postLogin') }}" method="post">
                @csrf
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" id="exampleInputEmail1" value="{{old('email')}}" name="email" placeholder="Ex : mail@mail.com">
                    @error('email')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                    @if(session()->has('error'))
                        <small class="text-danger">{{session('error')}}</small>
                    @endif
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" name="password" placeholder="Password">
                    @error('password')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="mt-3">
                  <button class="btn btn-block btn-info btn-lg font-weight-medium auth-form-btn" type="submit">SIGN IN</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="{{ asset('template/vendors') }}/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{ asset('template') }}/js/off-canvas.js"></script>
  <script src="{{ asset('template') }}/js/hoverable-collapse.js"></script>
  <script src="{{ asset('template') }}/js/template.js"></script>
  <!-- endinject -->
</body>

</html>
