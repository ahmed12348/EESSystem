<!doctype html>
<html lang="en" dir="ltr">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/images/favicon-32x3ss2.png" type="image/png" />
  <!-- Bootstrap CSS -->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">

  {{-- <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600&family=Inter:wght@400;500&display=swap" rel="stylesheet"> --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <style>
    body {
      background: url("{{ asset('assets/images/Login.png') }}") no-repeat center center fixed;
    }
    .img-fluid {
      max-width: 25% !important;
    }
    .password-wrapper {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }
  </style>

  <title>EES - Admin Login</title>
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
                  <h5 class="card-title">Admin Login</h5>
                  <p class="card-text mb-4">Enter your email and password to access the admin panel.</p>

                  <!-- Alerts -->
                  @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                  @endif
                  @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                  @endif

                  <form method="POST" class="form-body" action="{{ route('admin.login')}}">
                    @csrf
                    <div class="row g-3">
                      
                      <!-- Email Input -->
                      <div class="col-12 text-start">
                        <label for="inputEmail" class="form-label">Email Address</label>
                        <div class="ms-auto position-relative">
                          <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="inputEmail" name="email" placeholder="Enter admin email" value="{{ old('email') }}" required>
                          @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                          @enderror
                        </div>
                      </div>

                      <!-- Password Input with Toggle -->
                      <div class="col-12 text-start">
                        <label for="inputChoosePassword" class="form-label">Password</label>
                        <div class="ms-auto position-relative password-wrapper">
                          <input type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" id="inputChoosePassword" placeholder="Enter password" required>
                          <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                          @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                          @enderror
                        </div>
                      </div>

                      <!-- Remember Me Checkbox -->
                      <div class="col-12 text-start">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                          <label class="form-check-label" for="rememberMe">Remember Me</label>
                        </div>
                      </div>

                      <!-- Sign In Button -->
                      <div class="col-12">
                        <div class="d-grid">
                          <button type="submit" class="btn btn-primary rounded-30">Sign In</button>
                        </div>
                      </div>

                    </div>
                  </form>

                  <!-- Forgot Password -->
                  <div class="mt-3">
                    <a href="#" class="text-primary">Forgot Password?</a>
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

  <!-- Password Toggle Script -->
  <script>
    document.getElementById("togglePassword").addEventListener("click", function () {
      const passwordInput = document.getElementById("inputChoosePassword");
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        this.classList.remove("bi-eye-slash");
        this.classList.add("bi-eye");
      } else {
        passwordInput.type = "password";
        this.classList.remove("bi-eye");
        this.classList.add("bi-eye-slash");
      }
    });
  </script>

</body>
</html>
