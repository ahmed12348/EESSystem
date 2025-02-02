<!doctype html>
<html lang="en" dir="ltr">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset('assets/images/favicon-3s2x32.png') }}" type="image/png" />
  
  <!-- Bootstrap CSS -->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
  {{-- <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600&family=Inter:wght@400;500&display=swap" rel="stylesheet"> --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">

  <style>
    body {
      background: url("{{ asset('assets/images/Login.png') }}") no-repeat center center fixed;
    }
    .img-fluid {
      max-width: 20% !important;
    }
    .p-sm-5 {
      padding: 1.5rem !important;
    }
    .form-control {
      border-radius: 8px;
      padding-left: 40px;
    }
    .input-group-text {
      background: transparent;
      border-right: none;
    }
    .input-group .form-control {
      border-left: none;
    }
  </style>

  <title>EES - Vendor Registration</title>
</head>

<body>

  <div class="wrapper">
    <main class="authentication-content">
      <div class="container-fluid">
        <div class="authentication-card">
          <div class="card shadow rounded-5 overflow-hidden mt-3">
            <div class="row g-0">
              <div class="col-lg-12">
                <div class="card-body p-4 p-sm-5 text-center"> 

                  <!-- Logo -->
                  <img src="{{ asset('assets/images/group.png') }}" class="img-fluid login-logo mb-3" alt="Logo">

                  <!-- Title & Subtitle -->
                  <h5 class="card-title">Vendor Registration</h5>
                  <p class="card-text mb-4">Fill in the details to create your vendor account</p>

                       
                  @include('admin.layouts.alerts')

                  <form method="POST" class="form-body" action="{{ route('vendor.register') }}">
                    @csrf

                    <div class="row g-3">
                      
                      <!-- Business Name -->
                      <div class="col-12 text-start">
                        <label for="businessName" class="form-label">Business Name</label>
                        <div class="input-group">
                          <span class="input-group-text"><i class="bi bi-building"></i></span>
                          <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                            id="businessName" name="business_name" placeholder="Enter Business Name" value="{{ old('business_name') }}" required>
                        </div>
                        @error('business_name')
                          <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                      </div>

                      <!-- Business Number -->
                      <div class="col-12 text-start">
                        <label for="businessNumber" class="form-label">Business Number</label>
                        <div class="input-group">
                          <span class="input-group-text"><i class="bi bi-hash"></i></span>
                          <input type="text" class="form-control @error('business_number') is-invalid @enderror"
                            id="businessNumber" name="business_number" placeholder="Enter Business Number" value="{{ old('business_number') }}" required>
                        </div>
                        @error('business_number')
                          <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                      </div>

                      <!-- Email Address -->
                      <div class="col-12 text-start">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                          <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" name="email" placeholder="Enter Email Address" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                          <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                      </div>

                      <!-- Phone Number -->
                      <div class="col-12 text-start">
                        <label for="phone" class="form-label">Phone Number</label>
                        <div class="input-group">
                          <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                          <input type="number" class="form-control @error('phone') is-invalid @enderror"
                            id="phone" name="phone" placeholder="Enter Phone Number" value="{{ old('phone') }}" required>
                        </div>
                        @error('phone')
                          <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                      </div>

                      <!-- Password -->
                      <div class="col-6 text-start">
                        <label for="password" class="form-label">Enter Password</label>
                        <div class="input-group">
                          <span class="input-group-text"><i class="bi bi-lock"></i></span>
                          <input type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" id="password" placeholder="Enter Password" required>
                        </div>
                        @error('password')
                          <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                      </div>

                      <!-- Confirm Password -->
                      <div class="col-6 text-start">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <div class="input-group">
                          <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                          <input type="password" class="form-control"
                            name="password_confirmation" id="confirmPassword" placeholder="Confirm Password" required>
                        </div>
                      </div>

                      <!-- Register Button -->
                      <div class="col-12">
                        <div class="d-grid">
                          <button type="submit" class="btn btn-primary mt-2">Register</button>
                        </div>
                      </div>

                      <!-- Login Link -->
                      <div class="col-12 text-center">
                        <p>Already have an account? <a href="{{ route('vendor.login') }}" class="text-primary">Login here</a></p>
                      </div>

                    </div> <!-- End Row -->
                  </form>

                </div> <!-- End Card Body -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Plugins -->
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/pace.min.js') }}"></script>

</body>
</html>
