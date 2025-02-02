@extends('vendor.layouts.app')

@section('content')

<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-4">
    <div class="col">
        <div class="card overflow-hidden radius-10">
            <div class="card-body">
                <div class="d-flex align-items-stretch justify-content-between overflow-hidden">
                    <div class="w-50">
                        <p>Total Orders</p>
                        <h4 class="">{{ $totalOrders }}</h4> <!-- Dynamically display the total orders -->
                    </div>
                    <div class="w-50">
                        <p class="mb-3 float-end text-success">+ 16% <i class="bi bi-arrow-up"></i></p>
                        <div id="chart1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card overflow-hidden radius-10">
            <div class="card-body">
                <div class="d-flex align-items-stretch justify-content-between overflow-hidden">
                    <div class="w-50">
                        <p>Total Views</p>
                        <h4 class="">{{ number_format($totalViews) }} </h4> <!-- Dynamically display total views -->
                    </div>
                    <div class="w-50">
                        <p class="mb-3 float-end text-danger">- 3.4% <i class="bi bi-arrow-down"></i></p>
                        <div id="chart2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card overflow-hidden radius-10">
            <div class="card-body">
                <div class="d-flex align-items-stretch justify-content-between overflow-hidden">
                    <div class="w-50">
                        <p>Revenue</p>
                        <h4 class="">${{ number_format($totalRevenue, 2) }}</h4> <!-- Dynamically display total revenue -->
                    </div>
                    <div class="w-50">
                        <p class="mb-3 float-end text-success">+ 24% <i class="bi bi-arrow-up"></i></p>
                        <div id="chart3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card overflow-hidden radius-10">
            <div class="card-body">
                <div class="d-flex align-items-stretch justify-content-between overflow-hidden">
                    <div class="w-50">
                        <p>Customers</p>
                        <h4 class="">{{ number_format($totalCustomers) }}</h4> <!-- Dynamically display total customers -->
                    </div>
                    <div class="w-50">
                        <p class="mb-3 float-end text-success">+ 8.2% <i class="bi bi-arrow-up"></i></p>
                        <div id="chart4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--end row-->
<div class="row">
  <div class="col-12 col-lg-6 d-flex">
    <div class="card radius-10 w-100">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <h6 class="mb-0">Revenue</h6>
          <div class="fs-5 ms-auto dropdown">
             <div class="dropdown-toggle dropdown-toggle-nocaret cursor-pointer" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></div>
               <ul class="dropdown-menu">
                 <li><a class="dropdown-item" href="#">Action</a></li>
                 <li><a class="dropdown-item" href="#">Another action</a></li>
                 <li><hr class="dropdown-divider"></li>
                 <li><a class="dropdown-item" href="#">Something else here</a></li>
               </ul>
           </div>
         </div>
        <div id="chart5"></div>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-6 d-flex">
    <div class="card radius-10 w-100">
      <div class="card-body">
        <div class="d-flex align-items-center">
           <h6 class="mb-0">By Device</h6>
           <div class="fs-5 ms-auto dropdown">
              <div class="dropdown-toggle dropdown-toggle-nocaret cursor-pointer" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></div>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-2 g-3 mt-2 align-items-center">
          <div class="col-lg-7 col-xl-7 col-xxl-8">
            <div class="by-device-container">
               <div class="piechart-legend">
                  <h2 class="mb-1">85%</h2>
                  <h6 class="mb-0">Total Visitors</h6>
               </div>
              <canvas id="chart6"></canvas>
            </div>
          </div>
          <div class="col-lg-5 col-xl-5 col-xxl-4">
            <div class="">
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                  <i class="bi bi-tablet-landscape-fill me-2 text-primary"></i> <span>Tablet - </span> <span>22.5%</span>
                </li>
                <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                  <i class="bi bi-phone-fill me-2 text-primary-2"></i> <span>Mobile - </span> <span>62.3%</span>
                </li>
                <li class="list-group-item d-flex align-items-center justify-content-between border-0">
                  <i class="bi bi-display-fill me-2 text-primary-3"></i> <span>Desktop - </span> <span>15.2%</span>
                </li>
              </ul>
             </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!--end row-->

@endsection


@push('scripts')
{{-- <script>
  var chartData = {
      labels: ['Jan', 'Feb', 'Mar', 'Apr'],
      datasets: [{
          label: 'Orders',
          data: [{{ $vendorOrdersJan }}, {{ $vendorOrdersFeb }}, {{ $vendorOrdersMar }}, {{ $vendorOrdersApr }}],
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
      }]
  };

  var ctx = document.getElementById('chart1').getContext('2d');
  var chart = new Chart(ctx, {
      type: 'line',
      data: chartData,
      options: {
          responsive: true
      }
  });
</script> --}}

@endpush