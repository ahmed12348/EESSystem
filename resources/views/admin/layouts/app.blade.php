<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  <!-- ✅ Define asset path dynamically based on language -->
  @php
      $assetPath = app()->getLocale() === 'ar' ? 'assets-rtl' : 'assets';
  @endphp

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset("$assetPath/images/groups.png") }}" type="image/png" />

  <!-- Plugins -->
  <link href="{{ asset("$assetPath/plugins/vectormap/jquery-jvectormap-2.0.2.css") }}" rel="stylesheet"/>
  <link href="{{ asset("$assetPath/plugins/simplebar/css/simplebar.css") }}" rel="stylesheet" />
  <link href="{{ asset("$assetPath/plugins/perfect-scrollbar/css/perfect-scrollbar.css") }}" rel="stylesheet" />
  <link href="{{ asset("$assetPath/plugins/metismenu/css/metisMenu.min.css") }}" rel="stylesheet" />

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

  <!-- ✅ Bootstrap & Custom Styles -->
  <link href="{{ asset("$assetPath/css/bootstrap.min.css") }}" rel="stylesheet" />
  <link href="{{ asset("$assetPath/css/bootstrap-extended.css") }}" rel="stylesheet" />
  <link href="{{ asset("$assetPath/css/style.css") }}" rel="stylesheet" />
  <link href="{{ asset("$assetPath/css/icons.css") }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ asset("$assetPath/css/pace.min.css") }}" rel="stylesheet" />

  <title>EES</title>
</head>

<!-- ✅ Add RTL direction dynamically -->
<body @if(app()->getLocale() === 'ar') dir="rtl" @endif>

  <!-- Start Wrapper -->
  <div class="wrapper">

      <!-- Header -->
      <header class="top-header">
        @include('admin.layouts.header')
      </header>

      <!-- Sidebar -->
      <aside class="sidebar-wrapper" data-simplebar="true">
          @include('admin.layouts.sidebar')
      </aside>

      <!-- Alerts -->
      <main class="page-content">
        @include('admin.layouts.alerts')
        @yield('content')
      </main>

      <!-- Footer -->
      @include('admin.layouts.footer')

  </div>
  <!-- End Wrapper -->

  <!-- ✅ Bootstrap Bundle JS -->
  <script src="{{ asset("$assetPath/js/bootstrap.bundle.min.js") }}"></script>

  <!-- Plugins -->
  <script src="{{ asset("$assetPath/js/jquery.min.js") }}"></script>
  <script src="{{ asset("$assetPath/plugins/simplebar/js/simplebar.min.js") }}"></script>
  <script src="{{ asset("$assetPath/plugins/metismenu/js/metisMenu.min.js") }}"></script>
  <script src="{{ asset("$assetPath/plugins/perfect-scrollbar/js/perfect-scrollbar.js") }}"></script>
  <script src="{{ asset("$assetPath/plugins/vectormap/jquery-jvectormap-2.0.2.min.js") }}"></script>
  <script src="{{ asset("$assetPath/plugins/vectormap/jquery-jvectormap-world-mill-en.js") }}"></script>
  <script src="{{ asset("$assetPath/js/pace.min.js") }}"></script>
  <script src="{{ asset("$assetPath/plugins/chartjs/js/Chart.min.js") }}"></script>
  <script src="{{ asset("$assetPath/plugins/chartjs/js/Chart.extension.js") }}"></script>
  <script src="{{ asset("$assetPath/plugins/apexcharts-bundle/js/apexcharts.min.js") }}"></script>

  <!-- App JS -->
  <script src="{{ asset("$assetPath/js/app.js") }}"></script>
  <script src="{{ asset("$assetPath/js/index.js") }}"></script>

  <script>
    new PerfectScrollbar(".best-product");
  </script>

  <!-- DataTables Core -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

  <script>
    $(document).ready(function () {
        $('#ordersTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthMenu": [10, 25, 50, 100],
        });
    });
  </script>

  @stack('scripts')

</body>
</html>
