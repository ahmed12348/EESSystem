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
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <style>
    body {
      background: url("{{ asset('assets/images/Login.png') }}") no-repeat center center fixed;
    }
    .img-fluid {
      max-width: 25% !important;
    }
  </style>

  <title>EES - OTP Verification</title>
</head>

<body>

  <div class="wrapper">

    <main class="authentication-content">
      <div class="container-fluid">
        <div class="authentication-card">
          <div class="card shadow rounded-5 overflow-hidden">
            <div class="row g-0">
              <div class="col-lg-12">
                <div class="card-body p-4 p-sm-5 text-center">

                  <!-- Logo -->
                  <img src="{{ asset('assets/images/group.png') }}" class="img-fluid login-logo mb-3" alt="Logo">

                  <!-- Title & Subtitle -->
                  <h5 class="card-title">OTP Verification</h5>
                  <p class="card-text mb-4">Please enter the OTP sent to your phone number</p>
                  @if(session('status'))
                  <div class="alert alert-success">
                      {{ session('status') }}
                  </div>
                  @endif
                  
                  @if($errors->any())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
                  <!-- OTP Verification Form -->
                  <form method="POST" class="form-body" action="{{ route('vendor.otp.verify') }}">
                    @csrf

                    {{-- <input type="hidden" name="phone" value="{{ $phone }}"> --}}
                    <input type="hidden" name="phone" value="{{ request('phone') }}">
                    
                    
                    <div class="row g-3">

                      <!-- OTP Input -->
                      <div class="col-12 text-start">
                        <label for="inputOtp" class="form-label">Enter OTP</label>
                        <div class="ms-auto position-relative">
                          <input type="password" class="form-control  @error('otp') is-invalid @enderror"
                            id="inputOtp" name="otp" placeholder="Enter OTP" required>
                          @error('otp')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                          @enderror
                          
                        </div>
                      </div>

                      <!-- Submit Button -->
                      <div class="col-12">
                        <div class="d-grid">
                          <button type="submit" class="btn btn-primary ">Verify OTP</button>
                        </div>
                      </div>

                    </div>
                  </form>

                  <!-- Link back to Login page -->
                  <div class="mt-3">
                    {{-- <p>Didn't receive OTP? <a href="#" class="text-primary">Resend OTP</a></p> --}}
                    <p>Already have an account? <a href="{{ route('vendor.login') }}" class="text-primary">Login here</a></p>
                  </div>

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
