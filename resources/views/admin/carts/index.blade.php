@extends('admin.layouts.app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Expired Cart Items</h4>
        <form action="{{ route('admin.cart.readd') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm">
                <i class="bi bi-arrow-clockwise"></i> Re-add All Expired Items
            </button>
        </form>
    </div>

    @if ($expiredItems->count() > 0)
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Expired Order Items</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Vendor</th>
                        <th>Order ID</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expiredItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->order->vendor->business_name ?? 'N/A' }}</td>
                        <td>{{ $item->order_id }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td><span class="badge bg-danger">Expired</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    
    <p class="text-center text-muted">No expired items found.</p>
    @endif
</div>

@endsection
