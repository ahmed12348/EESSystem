@extends('admin.layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Admin Dashboard</h4>
    <a href="{{ route('admin.export_admin') }}" class="btn btn-success btn-sm px-2" >
        <i class="bi bi-download"></i> Export Data
    </a>
</div>

<div class="row row-cols-1 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-3">
    @php
        $kpis = [
            ['title' => 'Total Orders', 'value' => $totalOrders > 0 ? $totalOrders : '0', 'icon' => 'bi bi-cart'],
            ['title' => 'Total Revenue', 'value' => '$'.($totalRevenue > 0 ? number_format($totalRevenue, 2) : '0.00'), 'icon' => 'bi bi-currency-dollar'],
            ['title' => 'Customers', 'value' => $totalCustomers > 0 ? number_format($totalCustomers) : '0', 'icon' => 'bi bi-people'],
            ['title' => 'Total Products', 'value' => $totalProducts > 0 ? number_format($totalProducts) : '0', 'icon' => 'bi bi-box'],
            ['title' => 'Avg Order Value', 'value' => '$'.($averageOrderValue > 0 ? number_format($averageOrderValue, 2) : '0.00'), 'icon' => 'bi bi-graph-up'],
            ['title' => 'Pending Orders', 'value' => $pendingOrders > 0 ? $pendingOrders : '0', 'icon' => 'bi bi-hourglass-split'],
        ];
    @endphp
    
    @foreach($kpis as $kpi)
    <div class="col">
        <div class="card overflow-hidden radius-10 text-center">
            <div class="card-body">
                <i class="{{ $kpi['icon'] }} fs-1 text-primary"></i>
                <p class="mb-1">{{ $kpi['title'] }}</p>
                <h4 class="fw-bold mb-0">{{ $kpi['value'] }}</h4>
            </div>
        </div>
    </div>
    @endforeach
</div>


<div class="row mt-4">
    <div class="col-12 col-lg-12 d-flex">
        <div class="card radius-10 w-100">
            <div class="card-body">
                <h6 class="mb-3">Latest Orders</h6>
                <table class="table table-bordered" id="ordersTable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Vendor</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->vendor->name ?? 'N/A' }}</td>
                            <td>{{ $order->customer->name ?? 'N/A' }}</td>
                            <td>${{ $order->total_price > 0 ? number_format($order->total_price, 2) : '0.00' }}</td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No Orders Available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')



@endpush
