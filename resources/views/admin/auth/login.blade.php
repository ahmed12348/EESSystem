<!doctype html>
<html lang="en" dir="ltr">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/images/favicon-32x3ss2.png" type="image/png" />
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
      background: url("{{ asset('assets/images/Login.png') }}") no-repeat center center fixed;
    }
    .img-fluid {
      max-width: 25% !important;
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
      <div class="card shadow rounded-5 overflow-hidden">
        <div class="row g-0">
          <div class="col-lg-12">
            <div class="card-body p-4 p-sm-5 text-center"> <!-- Center Content -->
              
              <!-- Logo -->
              <img src="{{ asset('assets/images/group.png') }}" class="img-fluid login-logo mb-3" alt="Logo">

              <!-- Title & Subtitle -->
              <h5 class="card-title">Login to Account</h5>
              <p class="card-text mb-4">Please enter your email and password to continue</p>

              
              <form method="POST" class="form-body" action="{{ route('admin.login') }}">
              @csrf
                <div class="row g-3">
                  @if(session('success'))
                  <div class="alert alert-success">
                      {{ session('success') }}
                  </div>
                  @endif
                
                <!-- Check if there is an error message in the session -->
                @if(session('error'))
                  <div class="alert alert-danger">
                      {{ session('error') }}
                  </div>
                @endif
                  <!-- Phone Input -->
                  <div class="col-12 text-start">
                    <label for="inputPhone" class="form-label">Phone Number</label>
                    <div class="ms-auto position-relative">
                      <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                       
                      </div>
                      <input type="text" class="form-control  @error('phone') is-invalid @enderror"
                        id="inputPhone" name="phone" placeholder="Enter Phone Number" value="{{ old('phone') }}" required>
                      @error('phone')
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
                      <input type="password" class="form-control   @error('password') is-invalid @enderror"
                        name="password" id="inputChoosePassword" placeholder="Enter Password" >
                      @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                      @enderror
                    </div>
                  </div>

                  <!-- Sign In Button -->
                  <div class="col-12">
                    <div class="d-grid">
                      <button type="submit" class="btn btn-primary ">Sign In</button>
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
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/pace.min.js') }}"></script>


</body>

</html>