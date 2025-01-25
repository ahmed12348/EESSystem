{{-- <!doctype html>
<html lang="en" dir="ltr">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/images/favicon-32x3s2.png" type="image/png" />
  <!-- Bootstrap CSS -->

  <link href=" {{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
  <!-- <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet"> -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600&family=Inter:wght@400;500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <style>
     body {
      background-color: #82A7E7; /* Black background */
    }
    .img-fluid {
      max-width: 40% !important;
    }

 </style>
  <!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />

  <title>EES</title>
</head>

<body>

  <!--start wrapper-->
  <div class="wrapper">
    
  
<main class="authentication-content">
  <div class="container-fluid">
    <div class="authentication-card">
      <div class="card shadow rounded-0 overflow-hidden">
        <div class="row g-0">
          <div class="col-lg-12">
            <div class="card-body p-4 p-sm-5 text-center"> <!-- Center Content -->
              
              <!-- Logo -->
              <img src="{{ asset('assets/images/group.png') }}" class="img-fluid login-logo mb-3" alt="Logo">

              <!-- Title & Subtitle -->
              <h5 class="card-title">Login to Account</h5>
              <p class="card-text mb-4">Please enter your email and password to continue</p>

              
              <form method="POST" class="form-body" action="{{ route('login') }}">
              @csrf
                <div class="row g-3">
                  
                  <!-- Email Input -->
                  <div class="col-12 text-start">
                    <label for="inputEmailAddress" class="form-label">Email Address</label>
                    <div class="ms-auto position-relative">
                      <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                       
                      </div>
                      <input type="email" class="form-control radius-30 ps-5 @error('email') is-invalid @enderror"
                        id="inputEmailAddress" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                      @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>

                  <!-- Password Input -->
                  <div class="col-12  text-start">
                    <label for="inputChoosePassword" class="form-label">Enter Password</label>
                    <div class="ms-auto position-relative">
                      <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                       
                      </div>
                      <input type="password" class="form-control radius-30 ps-5 @error('password') is-invalid @enderror"
                        name="password" id="inputChoosePassword" placeholder="Enter Password" required>
                      @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>

                  <!-- Sign In Button -->
                  <div class="col-12">
                    <div class="d-grid">
                      <button type="submit" class="btn btn-primary radius-30">Sign In</button>
                    </div>
                  </div>

                </div>
              </form>

            </div> <!-- End Card Body -->
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<!--end content-->

        
       <!--end page main-->

  </div>
  <!--end wrapper-->


  <!--plugins-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/pace.min.js"></script>


</body>

</html> --}}