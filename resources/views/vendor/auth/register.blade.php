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
    .p-sm-5 {
        padding: 1.5rem !important;
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
          
              <form method="POST" class="form-body" action="{{ route('vendor.register') }}">
                @csrf

                <div class="row g-1">
                  
                  <!-- Name Input -->
                  <div class="col-12 text-start">
                    <label for="inputName" class="form-label">Business Name</label>
                    <div class="ms-auto position-relative">
                      <input type="text" class="form-control  @error('business_name') is-invalid @enderror"
                        id="inputName" name="business_name" placeholder="Enter Business Name" value="{{ old('business_name') }}" required>
                      @error('business_name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>
    
                  <!-- Name Input -->
                  <div class="col-12 text-start">
                    <label for="inputName" class="form-label">Business Number
                    </label>
                    <div class="ms-auto position-relative">
                      <input type="text" class="form-control  @error('business_number') is-invalid @enderror"
                        id="inputName" name="business_number" placeholder="Enter Business Name" value="{{ old('business_number') }}" required>
                      @error('business_number')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>

                  <!-- Email Input -->
                  <div class="col-12 text-start">
                    <label for="inputEmailAddress" class="form-label">Email Address</label>
                    <div class="ms-auto position-relative">
                      <input type="email" class="form-control  @error('email') is-invalid @enderror"
                        id="inputEmailAddress" name="email" placeholder="Enter Email Address" value="{{ old('email') }}" required>
                      @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>

                  <!-- Phone Number Input -->
                  <div class="col-12 text-start">
                    <label for="inputPhoneNumber" class="form-label">Phone Number</label>
                    <div class="ms-auto position-relative">
                      <input type="number" class="form-control  @error('phone') is-invalid @enderror"
                        id="inputPhoneNumber" name="phone" placeholder="Enter Phone Number" value="{{ old('phone') }}" required>
                      @error('phone')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>

                  <!-- Password Input -->
                  <div class="col-6 text-start">
                    <label for="inputChoosePassword" class="form-label">Enter Password</label>
                    <div class="ms-auto position-relative">
                      <input type="password" class="form-control  @error('password') is-invalid @enderror"
                        name="password" id="inputChoosePassword" placeholder="Enter Password" required>
                      @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>

                  <!-- Confirm Password Input -->
                  <div class="col-6 text-start">
                    <label for="inputConfirmPassword" class="form-label">Confirm Password</label>
                    <div class="ms-auto position-relative">
                      <input type="password" class="form-control " name="password_confirmation"
                        id="inputConfirmPassword" placeholder="Confirm Password" required>
                    </div>
                  </div>

                  <!-- Register Button -->
                  <div class="col-12">
                    <div class="d-grid">
                      <button type="submit" class="btn btn-primary mt-2">Register</button>
                    </div>
                  </div>

                </div>
              </form>

              <!-- Already have an account -->
              <div class="mt-3">
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
