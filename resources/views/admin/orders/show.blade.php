@extends('admin.layouts.app')

@section('title', __('messages.view_order'))

@section('content')
    <div class="container">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('messages.orders') }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('messages.order_details') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5>{{ __('messages.order') }} #{{ $order->id }}</h5>
                <hr>

                <p><strong>{{ __('messages.customer') }}:</strong> {{ $order->customer->name }}</p>
                <p><strong>{{ __('messages.vendor') }}:</strong> {{ $order->vendor->business_name }}</p>
                <p><strong>{{ __('messages.status') }}:</strong> <span class="badge bg-info">{{ ucfirst($order->status) }}</span></p>
                <p><strong>{{ __('messages.placed_at') }}:</strong> {{ $order->placed_at->format('d M Y H:i') }}</p>

                <h6>{{ __('messages.products') }}:</h6>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('messages.product') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{ __('messages.price') }}</th>
                            <th>{{ __('messages.discount') }}</th>
                            <th>{{ __('messages.final_price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->discount }}%</td>
                                <td>${{ number_format($item->final_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h6>{{ __('messages.total_price') }}: ${{ number_format($order->total_price, 2) }}</h6>

                <!-- Notes Section -->
                <h6 class="mt-3">{{ __('messages.notes') }}</h6>
                <div class="card border p-3">
                    @if($order->notes)
                        <p>{{ $order->notes }}</p>
                    @else
                        <p class="text-muted">{{ __('messages.no_notes_available') }}</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
