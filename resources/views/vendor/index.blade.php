@extends('vendor.layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">{{ __('messages.vendor_dashboard') }}</h4>
    <a href="{{ route('vendor.export') }}" class="btn btn-success btn-sm px-2">
        <i class="bi bi-download"></i> {{ __('messages.export') }}
    </a>
</div>

<!-- KPI Cards -->
<div class="row row-cols-1 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-3">
    @php
        $kpis = [
            ['title' => __('messages.total_orders'), 'value' => $totalOrders > 0 ? $totalOrders : '0', 'icon' => 'bi bi-cart'],
            ['title' => __('messages.total_revenue'), 'value' => '$'.($totalRevenue > 0 ? number_format($totalRevenue, 2) : '0.00'), 'icon' => 'bi bi-currency-dollar'],
            ['title' => __('messages.customers'), 'value' => $totalCustomers > 0 ? number_format($totalCustomers) : '0', 'icon' => 'bi bi-people'],
            ['title' => __('messages.total_products'), 'value' => $totalProducts > 0 ? number_format($totalProducts) : '0', 'icon' => 'bi bi-box'],
            ['title' => __('messages.avg_order_value'), 'value' => '$'.($averageOrderValue > 0 ? number_format($averageOrderValue, 2) : '0.00'), 'icon' => 'bi bi-graph-up'],
            ['title' => __('messages.pending_orders'), 'value' => $pendingOrders > 0 ? $pendingOrders : '0', 'icon' => 'bi bi-hourglass-split'],
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

<!-- Tab Navigation -->
<ul class="nav nav-tabs mt-4" id="vendorDashboardTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="latest-orders-tab" data-bs-toggle="tab" data-bs-target="#latest-orders" type="button" role="tab">
            {{ __('messages.latest_orders') }}
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="top-products-tab" data-bs-toggle="tab" data-bs-target="#top-products" type="button" role="tab">
            {{ __('messages.top_5_products') }}
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="top-customers-tab" data-bs-toggle="tab" data-bs-target="#top-customers" type="button" role="tab">
            {{ __('messages.top_5_customers') }}
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content mt-3" id="vendorDashboardTabsContent">

    <!-- Latest Orders -->
    <div class="tab-pane fade show active" id="latest-orders" role="tabpanel">
        <div class="card radius-10">
            <div class="card-body">
                <h6 class="mb-3">{{ __('messages.latest_orders') }}</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('messages.order_id') }}</th>
                            <th>{{ __('messages.customer') }}</th>
                            <th>{{ __('messages.amount') }}</th>
                            <th>{{ __('messages.date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->customer->name ?? __('messages.na') }}</td>
                            <td>${{ number_format($order->total_price, 2) }}</td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">{{ __('messages.na') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top 5 Selling Products -->
    <div class="tab-pane fade" id="top-products" role="tabpanel">
        <div class="card radius-10">
            <div class="card-body">
                <h6 class="mb-3">{{ __('messages.top_5_products') }}</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('messages.product_name') }}</th>
                            <th>{{ __('messages.sales_count') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->order_items_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top 5 Customers -->
    <div class="tab-pane fade" id="top-customers" role="tabpanel">
        <div class="card radius-10">
            <div class="card-body">
                <h6 class="mb-3">{{ __('messages.top_5_customers') }}</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('messages.customer') }}</th>
                            <th>{{ __('messages.total_spent') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCustomers as $customer)
                        <tr>
                            <td>{{ $customer->name ?? 'N/A' }} </td>
                            <td>${{ number_format($customer->total_spent, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
