<!doctype html>
<html lang="en" dir="ltr">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset('assets/images/favicon-32sx32.png') }}" type="image/png" />
  <!-- Bootstrap CSS -->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <style>
    body {
      background: url("{{ asset('assets/images/Login.png') }}") no-repeat center center fixed;
    }
    .img-fluid {
      max-width: 20% !important;
    }
  </style>

  <title>404 - Page Not Found</title>
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

                  <!-- 404 Title -->
                  <h1 class="display-1 fw-bold ">404</h1>
                  <h3 class="mb-3">Oops! Page Not Found</h3>
                  <p class="text-muted">The page you are looking for doesn't exist or has been moved.</p>

                  <!-- Back Button -->
                  <a href="{{ route('admin.index') }}" class="btn btn-primary">
                    <i class="bi bi-house-door"></i> Back to Admin Home
                  </a>

                </div>
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
