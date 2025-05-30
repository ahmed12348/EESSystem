@extends('admin.layouts.app')

@section('title', __('messages.orders_dashboard'))

@section('content')
<div class="container">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.orders') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.order_list') }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
      
            @can('orders-create')
            <a class="btn btn-info text-white" href="{{ route('admin.orders.create') }}">
                <i class="bi bi-plus-circle"></i> {{ __('messages.add_new_order') }}
            </a>
            @endcan
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">{{ __('messages.orders_list') }}</h5>
                <form class="ms-auto position-relative" method="GET" action="{{ route('admin.orders.index') }}">
                    <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <input class="form-control ps-5" type="text" name="search" placeholder="{{ __('messages.search') }}" value="{{ request()->query('search') }}">
                </form>
            </div>

            <div class="table-responsive mt-3">
                <table class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>{{ __('messages.id') }}</th>
                            <th>{{ __('messages.customer') }}</th>
                            <th>{{ __('messages.vendor') }}</th>
                            <th>{{ __('messages.address') }}</th>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->customer?->vendor?->business_name ?? __('messages.na') }}</td>
                                <td>{{ $order->vendor?->business_name ?? __('messages.na') }}</td>
                                <td>{{ $order->customer?->vendor?->location?->address ?? __('messages.na') }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>
                                    @if ($order->status == 'pending')
                                        <span class="badge bg-warning">{{ __('messages.pending') }}</span>
                                    @elseif ($order->status == 'completed')
                                        <span class="badge bg-success">{{ __('messages.completed') }}</span>
                                    @elseif ($order->status == 'cancelled')
                                        <span class="badge bg-danger">{{ __('messages.cancelled') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions d-flex align-items-center gap-2 fs-6">
                                       
                                        @can('orders-edit')
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="text-primary" data-bs-toggle="tooltip" title="{{ __('messages.edit') }}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @endcan
                                        @can('orders-view')
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-warning" data-bs-toggle="tooltip" title="{{ __('messages.view') }}">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        @endcan
                                        @can('orders-delete')
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger" data-bs-toggle="tooltip" title="{{ __('messages.delete') }}" onclick="return confirm('{{ __('messages.delete_confirmation') }}')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-2">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
